<?php
declare(strict_types=1);

namespace Doctrine\Orm\Command;

use Doctrine\Orm\Entity\User;
use Faker\Factory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Uid\Uuid;

final class OrmUserGetCommand extends OrmCommand
{
    protected static $defaultName = 'orm:user:get';

    protected function configure(): void
    {
        $this
            ->setDescription('Get users')
            ->addOption('count', 'c', InputOption::VALUE_OPTIONAL, 'Number of users to create', 100)
            ->addOption('list', 'l', InputOption::VALUE_NONE, 'Fetch as list')
            ->addOption('fill', 'f', InputOption::VALUE_NONE, 'Fill database with users');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $count = (int) $input->getOption('count');
        $list = $input->getOption('list');
        $fill = $input->getOption('fill');
        $this->stopwatch->start('overall');

        if ($fill) {
            $output->writeln('Create users in database');
            $this->createUsers($count);
        }

        if (false === $list) {
            $output->writeln('Single fetch mode');
            $this->singleFetch($count);
        } else {
            $output->writeln('List fetch mode');
            $this->listFetch($count);
        }

        $this->stopwatch->stop('overall');
        $this->presentStatistics($output, 'overall');

        return Command::SUCCESS;
    }

    private function singleFetch(int $count): void
    {
        for ($i = 0; $i < $count; $i++) {
            $this->orm->getRepository(User::class)->findOneBy([]);
        }
    }

    private function listFetch(int $count): void
    {
        for ($i = 0; $i < $count; $i++) {
            $this->orm->getRepository(User::class)->findAllWithLimit($count);
        }
    }

    private function createUsers(int $count): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < $count; $i++) {
            $user = new User(
                Uuid::v4(),
                $faker->firstName(),
                $faker->lastName,
                $faker->email,
                $faker->dateTime,
                $faker->dateTime
            );
            $this->orm->persist($user);
        }

        $this->orm->flush();
    }
}
