<?php

namespace BladeBuilder\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ClearCompiled extends Command
{
    protected $compiledPath;

    public function __construct($name, $projectPath)
    {
        $this->compiledPath = $projectPath.'/compiled';
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setName('clear:compiled')
            ->setDescription('Clear compiled views that aren\'t present in views directory');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $di = new \DirectoryIterator($this->compiledPath);
        foreach ($di as $filename => $file) {
            if (!$file->isDot()) {
                unlink($file->getPathname());
            }
        }
        $output->writeln("Compiled files cleared");
    }
}
