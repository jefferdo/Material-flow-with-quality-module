<?php
namespace BladeBuilder\Tests;

use org\bovigo\vfs\vfsStream;
use BladeBuilder\Commands\ClearCache;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ClearCacheTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        vfsStream::setup('root', null, [
            'cache' => [
                md5(uniqid().mt_rand()).'.php' => ''
            ],
        ]);
    }

    public function testExecute()
    {
        $application = new Application();
        $application->add(new ClearCache(null, vfsStream::url('root')));

        $command = $application->find('clear:cache');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName()]);

        $di = new \DirectoryIterator(vfsStream::url('root/cache'));
        $totalFiles = 0;
        foreach ($di as $filename => $file) {
            if (!$file->isDot()) {
                $totalFiles++;
            }
        }
        $this->assertTrue($totalFiles === 0);
    }
}
