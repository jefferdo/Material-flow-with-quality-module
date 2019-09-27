<?php

namespace BladeBuilder\Tests;

use org\bovigo\vfs\vfsStream;
use BladeBuilder\Commands\Init;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class InitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \org\bovigo\vfs\vfsStreamDirectory
     */
    protected $fs;

    public function setUp()
    {
        $this->fs = vfsStream::setup();
    }

    public function testExecute()
    {
        $application = new Application();
        $application->add(new Init(null, vfsStream::url('root')));

        $command = $application->find('init');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName()]);

        $this->assertTrue($this->fs->hasChild('views'));
        $this->assertTrue($this->fs->hasChild('cache'));
        $this->assertTrue($this->fs->hasChild('compiled'));
        $this->assertTrue($this->fs->hasChild('public'));
        $this->assertFileExists(vfsStream::url('root/public/index.php'));
        $this->assertRegExp('/Initialization complete!/', $commandTester->getDisplay());
    }
}
