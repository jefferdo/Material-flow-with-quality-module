<?php
namespace BladeBuilder\Tests;

use org\bovigo\vfs\vfsStream;
use BladeBuilder\Commands\ClearCompiled;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ClearCompiledTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        vfsStream::setup('root', null, [
            'compiled' => [
                'index.html' => ''
            ],
        ]);
    }

    public function testExecute()
    {
        $application = new Application();
        $application->add(new ClearCompiled(null, vfsStream::url('root')));

        $command = $application->find('clear:compiled');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName()]);

        $di = new \DirectoryIterator(vfsStream::url('root/compiled'));
        $totalFiles = 0;
        foreach ($di as $filename => $file) {
            if (!$file->isDot()) {
                $totalFiles++;
            }
        }
        $this->assertTrue($totalFiles === 0);
    }
}
