<?php

namespace BladeBuilder;

use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;
use Illuminate\Events\Dispatcher;

class App
{
    protected $viewsDir;
    protected $factory;
    protected $filesystem;

    public function __construct($viewsDir, $cacheDir)
    {
        $filesystem = new Filesystem();

        $engineResolver = new EngineResolver();
        $engineResolver->register('blade', function () use ($filesystem, $cacheDir) {
            return new CompilerEngine(
                new BladeCompiler($filesystem, $cacheDir)
            );
        });

        $factory = new Factory(
            $engineResolver,
            new FileViewFinder($filesystem, [$viewsDir]),
            new Dispatcher()
        );

        $this->factory = $factory;
        $this->viewsDir = $viewsDir;
        $this->filesystem = $filesystem;
    }

    public function renderView($view)
    {
        if (strpos($view, '_') === 0) {
            throw new \InvalidArgumentException('Invalid view');
        }

        $data = array_replace(
            $this->getJsonData('_global.json'),
            $this->getJsonData($view.'.json')
        );

        return $this->factory->make($view, $data)->render();
    }

    protected function getJsonData($file)
    {
        $data = [];
        if ($this->filesystem->exists($this->viewsDir.'/'.$file)) {
            $data = json_decode($this->filesystem->get($this->viewsDir.'/'.$file), true);
        }
        return $data;
    }
}
