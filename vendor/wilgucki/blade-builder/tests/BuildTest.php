<?php
namespace BladeBuilder\Tests;

use org\bovigo\vfs\vfsStream;
use BladeBuilder\Commands\Build;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class BuildTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        vfsStream::setup('root', null, [
            'views' => [
                '_layouts' => [
                    'app.blade.php' => '<p>@include("_partials.top")</p><div>@yield("content")</div>'
                ],
                '_partials' => [
                    'top.blade.php' => 'top'
                ],
                'index.blade.php' => 'hello world!',
                'about' => [
                    'index.blade.php' => 'about'
                ],
                'test.blade.php' => '@extends("_layouts.app")'.PHP_EOL.'@section("content") content @endsection'
            ],
            'cache' => [],
            'compiled' => []
        ]);
    }

    public function testExecute()
    {
        $application = new Application();
        $application->add(new Build(null, vfsStream::url('root')));

        $command = $application->find('build');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName()]);

        $this->assertFileExists(vfsStream::url('root/compiled/index.html'));
        $this->assertFileExists(vfsStream::url('root/compiled/about/index.html'));
        $this->assertFileExists(vfsStream::url('root/compiled/test.html'));
        $this->assertRegExp('|<p>top</p><div> content </div>|', file_get_contents(vfsStream::url('root/compiled/test.html')));
        $this->assertRegExp('/Done!/', $commandTester->getDisplay());
    }
}
