<?php

namespace BladeBuilder\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ClearCache extends Command
{
    protected $cachePath;

    public function __construct($name, $projectPath)
    {
        $this->cachePath = $projectPath.'/cache';
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setName('clear:cache')
            ->setDescription('Clear view cache');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $di = new \DirectoryIterator($this->cachePath);
        foreach ($di as $filename => $file) {
            if (!$file->isDot()) {
                unlink($file->getPathname());
            }
        }
        $output->writeln("Cache cleared");
    }
}
