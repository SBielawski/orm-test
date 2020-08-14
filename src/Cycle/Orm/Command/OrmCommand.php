<?php
declare(strict_types=1);

namespace Cycle\Orm\Command;

use Cycle\ORM\ORMInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Stopwatch\Stopwatch;

abstract class OrmCommand extends Command
{
    protected ORMInterface $orm;
    protected Stopwatch $stopwatch;

    public function __construct(ORMInterface $orm)
    {
        parent::__construct();
        $this->orm = $orm;
        $this->stopwatch = new Stopwatch();
    }

    protected function presentStatistics(OutputInterface $output, string $name): void
    {
        $event = $this->stopwatch->getEvent($name);
        $output->writeln(sprintf('------ %s ------', strtoupper($name)));
        $output->writeln(sprintf('Memory: %d kB', $event->getMemory()/1024));
        $output->writeln(sprintf('Time: %d ms', (int) $event->getDuration()));
    }
}
