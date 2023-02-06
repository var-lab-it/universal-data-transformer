<?php

declare(strict_types=1);

namespace App\Config;

use App\Config\Database\AbstractDatabaseConfiguration;
use App\Config\Transformation\AbstractTransformationConfiguration;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

class Configuration
{
    /**
     * @var array<int|string, array<int|string, array<int|string>>>
     */
    private array $configurationArray = [];

    private AbstractTransformationConfiguration $transformationConfiguration;

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

    public function getTransformationConfiguration(): AbstractTransformationConfiguration
    {
        return $this->transformationConfiguration;
    }

    public function getTransformerIdentifier(): string
    {
        return \sprintf(
            '%s.%s',
            $this->getTransformationConfiguration()->getSource(),
            $this->getTransformationConfiguration()->getTarget(),
        );
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

        $transformationConfigArray = $this->getConfigurationFromYaml()['transformation'];
        /** @phpstan-ignore-next-line */
        $this->transformationConfiguration = AbstractTransformationConfiguration::fromConfig($transformationConfigArray);
    }
}
