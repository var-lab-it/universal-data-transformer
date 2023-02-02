<?php

declare(strict_types=1);

namespace App\Tests\UnitTests\Command;

use App\Command\HelloWorldCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class HelloWorldCommandTest extends TestCase
{
    public function testExecute(): void
    {
        $command = new HelloWorldCommand();

        $inputMock = $this->getMockBuilder(InputInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $outputMock = $this->getMockBuilder(OutputInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $refClass = new \ReflectionClass($command);
        $method   = $refClass->getMethod('execute');

        $result = $method->invokeArgs($command, [
            'input'  => $inputMock,
            'output' => $outputMock,
        ]);

        self::assertEquals(Command::SUCCESS, $result);
    }
}
