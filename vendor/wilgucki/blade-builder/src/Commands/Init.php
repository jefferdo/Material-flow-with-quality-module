<?php

namespace BladeBuilder\Commands;

use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Init extends Command
{
    protected $projectPath;
    protected $viewsPath = '/views';
    protected $layoutPath = '/_layouts';
    protected $partialsPath = '/_partials';
    protected $cachePath = '/cache';
    protected $outputPath = '/compiled';
    protected $publicPath = '/public';

    public function __construct($name, $projectPath)
    {
        $this->projectPath = $projectPath;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setName('init')
            ->setDescription('Initialize project');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filesystem = new Filesystem();
        $filesystem->makeDirectory($this->projectPath.$this->viewsPath, 0775, false, true);
        $filesystem->makeDirectory($this->projectPath.$this->viewsPath.$this->layoutPath, 0775, false, true);
        $filesystem->makeDirectory($this->projectPath.$this->viewsPath.$this->partialsPath, 0775, false, true);
        $filesystem->makeDirectory($this->projectPath.$this->cachePath, 0775, false, true);
        $filesystem->makeDirectory($this->projectPath.$this->outputPath, 0775, false, true);
        $filesystem->makeDirectory($this->projectPath.$this->publicPath, 0775, false, true);

        $filesystem->copy(__DIR__.'/../index.php', $this->projectPath.$this->publicPath.'/index.php');

        $output->writeln("Initialization complete!");
    }
}
