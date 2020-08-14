<?php
declare(strict_types=1);

namespace Doctrine\Orm\Command;

use Doctrine\Orm\Entity\User;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Uid\Uuid;

final class OrmUserCreateCommand extends OrmCommand
{
    protected static $defaultName = 'orm:user:create';

    protected function configure(): void
    {
        $this
            ->setDescription('Create users')
            ->addOption('count', 'c', InputOption::VALUE_OPTIONAL, 'Number of users to create', 100)
            ->addOption('multi', 'm', InputOption::VALUE_NONE, 'Use multitransactions?');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $count = (int) $input->getOption('count');
        $multitransactions = $input->getOption('multi');
        $this->stopwatch->start('overall');

        if (false === $multitransactions) {
            $output->writeln('Single transaction mode');
            $this->singleTransaction($count);
        } else {
            $output->writeln('Multiple transactions mode');
            $this->multipleTransaction($count);
        }

        $this->stopwatch->stop('overall');
        $this->presentStatistics($output, 'overall');
        $this->presentStatistics($output, 'save');

        return Command::SUCCESS;
    }

    private function singleTransaction(int $count): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < $count; $i++) {
            $user = $this->createUser($faker);
            $this->orm->persist($user);
        }

        $this->stopwatch->start('save');
        $this->orm->flush();
        $this->stopwatch->stop('save');
    }

    private function multipleTransaction(int $count): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < $count; $i++) {
            $user = $this->createUser($faker);
            $this->orm->persist($user);
            $this->stopwatch->start('save');
            $this->orm->flush();
            $this->stopwatch->stop('save');
        }
    }

    private function createUser(Generator $faker): User
    {
        return new User(
            Uuid::v4(),
            $faker->firstName(),
            $faker->lastName,
            $faker->email,
            $faker->dateTime,
            $faker->dateTime
        );
    }
}
