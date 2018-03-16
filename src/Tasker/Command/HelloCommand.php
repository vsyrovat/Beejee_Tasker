<?php

namespace Tasker\Command;

use Framework\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class HelloCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('app:hello')
            ->setDescription('Say Hello')
            ->setHelp('This command say Hello to you');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Hello");
        $output->writeln(get_class($this->getFrameworkApplication()));
        $output->writeln($this->getProjectDirectory());
    }
}
