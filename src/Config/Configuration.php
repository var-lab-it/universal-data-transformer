<?php

declare(strict_types=1);

namespace App\Config;

use App\Config\Database\AbstractDatabaseConfiguration;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

class Configuration
{
    /**
     * @var array<int|string, array<int|string, array<int|string>>>
     */
    private array $configurationArray = [];

    private AbstractDatabaseConfiguration $databaseConfiguration;

    public function __construct(
        private readonly string $filePath,
        private readonly Filesystem $filesystem,
    ) {
        if (false === $this->filesystem->exists($filePath)) {
            throw new FileNotFoundException(
                "The file \"{$filePath}\" does not exist.",
            );
        }

        $this->load();
    }

    public function getDatabaseConfiguration(): AbstractDatabaseConfiguration
    {
        return $this->databaseConfiguration;
    }

    /**
     * @return array<int|string, array<int|string, array<int|string>>>
     */
    private function getConfigurationFromYaml(): array
    {
        if (0 === \count($this->configurationArray)) {
            /** @var array<int|string, array<int|string, array<int|string>>> $parsedYaml */
            $parsedYaml               = Yaml::parseFile($this->filePath);
            $this->configurationArray = $parsedYaml;
        }

        return $this->configurationArray;
    }

    private function load(): void
    {
        /** @var array{
         *     driver: string,
         *     host: string,
         *     database_name: string,
         *     user: string,
         *     password: string,
         * } $dbConfigArray */
        $dbConfigArray               = $this->getConfigurationFromYaml()['database']['connection'];
        $this->databaseConfiguration = AbstractDatabaseConfiguration::fromConfig($dbConfigArray);
    }
}
