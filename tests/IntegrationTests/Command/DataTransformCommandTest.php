<?php

declare(strict_types=1);

namespace App\Tests\IntegrationTests\Command;

use App\Command\DataTransformCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class DataTransformCommandTest extends TestCase
{
    public function testExecute(): void
    {
        $app = new Application();
        $app->add(new DataTransformCommand());

        $cmd = $app->find('data:transform');

        $cmdTester = new CommandTester($cmd);
        $cmdTester->execute([
            'config-file' => \dirname(__DIR__, 2) . '/testfiles/example_config.yaml',
        ]);

        $cmdTester->assertCommandIsSuccessful();

        $output = $cmdTester->getDisplay();

        self::assertStringContainsString('Loading configuration from:', $output);
    }
}
