<?php

declare(strict_types=1);

namespace App\Tests\UnitTests\Config;

use App\Config\Configuration;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Filesystem;

class ConfigurationTest extends TestCase
{
    use MatchesSnapshots;

    private Filesystem&MockObject $filesystemMock;

    protected function setUp(): void
    {
        $this->filesystemMock = $this->getMockBuilder(Filesystem::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testLoadWithExistingFile(): void
    {
        $this->filesystemMock
            ->method('exists')
            ->willReturn(true);

        $config = new Configuration(
            \dirname(__DIR__, 2) . '/testfiles/example_config.yaml',
            $this->filesystemMock,
        );

        $refClass = new \ReflectionClass($config);
        $method   = $refClass->getMethod('load');

        $method->invoke($config);

        $dbConfig = $config->getDatabaseConfiguration();

        self::assertEquals('pdo_mysql', $dbConfig->getDriver());
        self::assertEquals('localhost', $dbConfig->getHost());
        self::assertEquals('example_db', $dbConfig->getDatabaseName());
        self::assertEquals('test', $dbConfig->getUser());
        self::assertEquals('test', $dbConfig->getPassword());

        $transformationConfig = $config->getTransformationConfiguration();
        self::assertEquals('database', $transformationConfig->getSource());
        self::assertEquals('WordPress', $transformationConfig->getTarget());
        self::assertEquals([
            'post' => [
                'title' => [
                    'table' => 'news',
                    'column' => 'title',
                ],
                'pubDate' => [
                    'table' => 'news',
                    'column' => 'created_at',
                ],
                'content' => [
                    'table' => 'news',
                    'column' => 'content',
                ],
                'excerpt' => [
                    'table' => 'news',
                    'column' => 'excerpt',
                ],
            ]
        ], $transformationConfig->getMapping());
    }

    public function testLoadWithNotExistingFile(): void
    {
        $this->filesystemMock
            ->method('exists')
            ->willReturn(false);

        $this->expectException(FileNotFoundException::class);
        $this->expectExceptionMessage('The file "invalid" does not exist.');

        new Configuration(
            'invalid',
            $this->filesystemMock,
        );
    }

    public function testGetConfigurationFromYaml(): void
    {
        $this->filesystemMock
            ->method('exists')
            ->willReturn(true);

        $config = new Configuration(
            \dirname(__DIR__, 2) . '/testfiles/example_config.yaml',
            $this->filesystemMock,
        );

        $refClass = new \ReflectionClass($config);
        $method   = $refClass->getMethod('getConfigurationFromYaml');

        $result = $method->invoke($config);

        $this->assertMatchesJsonSnapshot($result);
    }
}
