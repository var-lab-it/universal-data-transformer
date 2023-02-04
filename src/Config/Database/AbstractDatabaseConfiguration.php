<?php

declare(strict_types=1);

namespace App\Config\Database;

use App\Exception\InvalidDatabaseConnectionException;
use App\Exception\InvalidDatabaseDriverException;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;

abstract class AbstractDatabaseConfiguration
{
    public const DRIVER_PDO_MYSQL = 'pdo_mysql';

    public const SUPPORTED_DRIVERS = [
        self::DRIVER_PDO_MYSQL => PdoMySqlConnection::class,
    ];

    /**
     * @throws InvalidDatabaseDriverException
     */
    public function __construct(
        private readonly string $driver,
        private readonly string $host,
        private readonly string $databaseName,
        private readonly string $user,
        private readonly string $password,
    ) {
    }

    /**
     * @param array{
     *     driver: string,
     *     host: string,
     *     database_name: string,
     *     user: string,
     *     password: string,
     * } $config
     *
     * @return AbstractDatabaseConfiguration|PdoMySqlConnection
     *
     * @throws InvalidDatabaseConnectionException
     */
    public static function fromConfig(array $config): self
    {
        $constraint = new Assert\Collection([
            'driver'        => new Assert\NotBlank([], 'The driver must not be empty.'),
            'host'          => new Assert\NotBlank([], 'The host must not be empty.'),
            'database_name' => new Assert\NotBlank([], 'The database_name must not be empty.'),
            'user'          => new Assert\NotBlank([], 'The user must not be empty.'),
            'password'      => new Assert\NotBlank([], 'The password must not be empty.'),
        ]);

        $validator = Validation::createValidator();

        $violations = $validator->validate($config, $constraint);

        if (\count($violations) > 0) {
            $errorMessage = 'The configuration for the database connection is invalid.';

            throw new InvalidDatabaseConnectionException($errorMessage);
        }

        $driver = $config['driver'];

        if (!isset(self::SUPPORTED_DRIVERS[$driver])) {
            throw new InvalidDatabaseDriverException(\sprintf(
                'The database driver "%s" is not supported.',
                $driver,
            ));
        }

        $classFQCN = self::SUPPORTED_DRIVERS[$driver];

        return new $classFQCN(
            driver: $config['driver'],
            host: $config['host'],
            databaseName: $config['database_name'],
            user: $config['user'],
            password: $config['password'],
        );
    }

    public function getDatabaseName(): string
    {
        return $this->databaseName;
    }

    public function getDriver(): string
    {
        return $this->driver;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getUser(): string
    {
        return $this->user;
    }
}
