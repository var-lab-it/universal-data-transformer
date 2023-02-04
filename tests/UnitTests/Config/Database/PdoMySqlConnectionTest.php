<?php

declare(strict_types=1);

namespace App\Tests\UnitTests\Config\Database;

use App\Config\Database\PdoMySqlConnection;
use App\Exception\InvalidDatabaseConnectionException;
use App\Exception\InvalidDatabaseDriverException;
use PHPUnit\Framework\TestCase;

class PdoMySqlConnectionTest extends TestCase
{
    public function testFromValidConfig(): void
    {
        $configArray = [
            'driver'        => 'pdo_mysql',
            'host'          => 'localhost',
            'database_name' => 'example_db',
            'user'          => 'test',
            'password'      => 'test',
        ];

        $result = PdoMySqlConnection::fromConfig($configArray);

        self::assertInstanceOf(PdoMySqlConnection::class, $result);

        self::assertEquals('pdo_mysql', $result->getDriver());
        self::assertEquals('localhost', $result->getHost());
        self::assertEquals('example_db', $result->getDatabaseName());
        self::assertEquals('test', $result->getUser());
        self::assertEquals('test', $result->getPassword());
    }

    public function testFromNotValidConfig(): void
    {
        $configArray = [
            'driver'        => 'pdo_mysql',
            'host'          => 'localhost',
            'database_name' => 'example_db',
            'password'      => 'test',
        ];

        $this->expectException(InvalidDatabaseConnectionException::class);
        $this->expectExceptionMessage('The configuration for the database connection is invalid.');

        /** @phpstan-ignore-next-line */
        PdoMySqlConnection::fromConfig($configArray);
    }

    public function testFromConfigWithInvalidDriver(): void
    {
        $configArray = [
            'driver'        => 'invalid-driver',
            'host'          => 'localhost',
            'database_name' => 'example_db',
            'user'          => 'test',
            'password'      => 'test',
        ];

        $this->expectException(InvalidDatabaseDriverException::class);
        $this->expectExceptionMessage('The database driver "invalid-driver" is not supported.');
        PdoMySqlConnection::fromConfig($configArray);
    }
}
