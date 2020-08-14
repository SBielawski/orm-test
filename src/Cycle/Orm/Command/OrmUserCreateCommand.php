<?php
declare(strict_types=1);

namespace Cycle\Orm\Command;

use Cycle\Orm\Entity\User;
use Cycle\ORM\Transaction;
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
        $transaction = new Transaction($this->orm);
        $faker = Factory::create();

        for ($i = 0; $i < $count; $i++) {
            $user = $this->createUser($faker);
            $transaction->persist($user);
        }

        $this->stopwatch->start('save');
        $transaction->run();
        $this->stopwatch->stop('save');
    }

    private function multipleTransaction(int $count): void
    {
        $transaction = new Transaction($this->orm);
        $faker = Factory::create();

        for ($i = 0; $i < $count; $i++) {
            $user = $this->createUser($faker);
            $transaction->persist($user);
            $this->stopwatch->start('save');
            $transaction->run();
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
