<?php

namespace AppBundle\Application;

use AppBundle\Command\InitCommand;
use AppBundle\Command\InstallCommand;
use Symfony\Component\Console\Application as ConsoleApp;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class App extends ConsoleApp
{
    protected function engage()
    {
        $this->setName("Composter v0.0.1");
        $this->add(new InitCommand());
        $this->add(new InstallCommand());
    }

    public function run(InputInterface $input = null, OutputInterface $output = null)
    {
        $this->engage();
        return parent::run($input, $output);
    }
}