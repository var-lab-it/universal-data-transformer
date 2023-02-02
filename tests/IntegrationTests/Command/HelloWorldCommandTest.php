<?php

declare(strict_types=1);

namespace App\Tests\IntegrationTests\Command;

use App\Command\HelloWorldCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class HelloWorldCommandTest extends TestCase
{
    public function testExecute(): void
    {
        $app = new Application();
        $app->add(new HelloWorldCommand());

        $cmd = $app->find('world:hello');

        $cmdTester = new CommandTester($cmd);
        $cmdTester->execute([]);

        $cmdTester->assertCommandIsSuccessful();

        $output = $cmdTester->getDisplay();

        self::assertStringContainsString('Hello world!', $output);
    }
}
