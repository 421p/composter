<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class InstallCommand extends Command
{
    protected function configure()
    {
        $this->setName('install')
            ->setDescription('installing headers to the vendor dir')
            ->addOption(
                'force',
                'f',
                InputOption::VALUE_OPTIONAL,
                'Will rewrite libraries if they are already installed.'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!file_exists('composter.json')) {
            $output->writeln('<error>No composter.json file found.</error>');
            return;
        }

        $isForced = $input->hasParameterOption(['--force', '-f']);

        $json = json_decode(file_get_contents('composter.json'), true);
        $dir = $json['vendors_path'] . '/';
        $vendors = $json['packages'];

        $output->writeln('Checking if everything is up to date...');

        foreach ($vendors as $key => $data) {
            list($vendor, $package) = explode('/', $key);

            if (!is_dir($dir . $vendor)) {
                mkdir($dir . $vendor, 0777, true);
            }

            $filepath = $dir . $vendor . '/' . $data['filename'];

            if (!file_exists($filepath) || $isForced) {
                $output->writeln(sprintf('Downloading %s ...', $key));

                $remote = fopen($this->buildUrl($vendor, $package, $data['revision'], $data['path']), 'r');
                $local = fopen($filepath, 'w');
                stream_copy_to_stream($remote, $local);
                fclose($remote);
                fclose($local);
            }
        }

        $output->writeln('<info>Done!</info>');

    }

    private function buildUrl($vendor, $package, $revision, $path)
    {
        return sprintf('https://raw.githubusercontent.com/%s/%s/%s/%s', $vendor, $package, $revision, $path);
    }
}