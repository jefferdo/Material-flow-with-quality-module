<?php

namespace BladeBuilder\Commands;

use BladeBuilder\App;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Build extends Command
{
    protected $viewsPath;
    protected $cachePath;
    protected $outputPath;
    
    public function __construct($name, $projectPath)
    {
        $this->viewsPath = $projectPath.'/views';
        $this->cachePath = $projectPath.'/cache';
        $this->outputPath = $projectPath.'/compiled';
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setName('build')
            ->setDescription('Build html files based on blade views');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $app = new App($this->viewsPath, $this->cachePath);
        $filesystem = new Filesystem();
        $di = new \RecursiveDirectoryIterator($this->viewsPath, \FilesystemIterator::SKIP_DOTS);
        
        foreach (new \RecursiveIteratorIterator($di) as $filename => $file) {
            $filePath = str_replace($this->viewsPath.'/', '', $filename);

            if (strpos($filePath, '_') === 0 || pathinfo($filename, PATHINFO_EXTENSION) == 'json') {
                continue;
            }

            $view = str_replace('.blade.php', '', $filePath);
            $pathInfo = pathinfo($view);

            if ($pathInfo['dirname'] == '.') {
                $viewDir = '/';
            } else {
                $viewDir = '/'.$pathInfo['dirname'].'/';
            }

            $dir = $this->outputPath.$viewDir;

            $filesystem->makeDirectory($dir, 0755, true, true);
            $filesystem->put(
                $dir.$pathInfo['filename'].'.html',
                $app->renderView($view)
            );
        }

        $output->writeln("Done!");
    }
}
