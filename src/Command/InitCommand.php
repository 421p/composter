<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitCommand extends Command
{
    protected function configure()
    {
        $this->setName('init')
            ->setDescription('Initializes a new composter.json file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!file_exists('composter.json')) {
            $dist = fopen(__DIR__.'/../../resources/composter.json.dist', 'r');
            $real = fopen('composter.json', 'w');
            stream_copy_to_stream($dist, $real);
            fclose($dist);
            fclose($real);

            $output->writeln('<info>composter.json was successfully created.</info>');
        } else {
            $output->writeln('<error>composter.json already exists.</error>');
        }


    }
}