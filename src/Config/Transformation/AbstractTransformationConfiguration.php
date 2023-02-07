<?php

declare(strict_types=1);

namespace App\Config\Transformation;

use App\Exception\InvalidDatabaseConnectionException;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;

abstract class AbstractTransformationConfiguration
{
    public const MAPPING_TARGET_WORDPRESS = 'WordPress';

    public const SUPPORTED_MAPPINGS = [
        self::MAPPING_TARGET_WORDPRESS => WordPressTransformationConfiguration::class,
    ];

    /**
     * @param array<string, string> $mapping
     */
    public function __construct(
        private readonly string $source,
        private readonly string $target,
        private readonly array $mapping,
    ) {
    }

    /**
     * @param array<int|string, string> $config
     *
     * @return static|WordPressTransformationConfiguration|self
     *
     * @throws InvalidDatabaseConnectionException
     */
    public static function fromConfig(array $config): self
    {
        $constraint = new Assert\Collection([
            'source'  => new Assert\NotBlank([], 'The source must not be empty.'),
            'target'  => new Assert\NotBlank([], 'The target must not be empty.'),
            'mapping' => new Assert\Optional(),
        ]);

        $validator = Validation::createValidator();

        $violations = $validator->validate($config, $constraint);

        if (\count($violations) > 0) {
            $errorMessage = 'The configuration for the data transformation/mapping is invalid.';

            throw new InvalidDatabaseConnectionException($errorMessage);
        }

        $mappingTarget = $config['target'];

        $classFQCN = self::SUPPORTED_MAPPINGS[$mappingTarget];

        /** @var array<string, string> $mappingArray */
        $mappingArray = $config['mapping'];

        /** @var self $classInstance */
        $classInstance = new $classFQCN(
            source: $config['source'],
            target: $config['target'],
            mapping: $mappingArray,
        );

        $mappingValidationConstraints = $classInstance->getMappingValidationConstraints();

        $classInstance->validateMapping($mappingArray, $mappingValidationConstraints);

        return $classInstance;
    }

    abstract public function getMappingValidationConstraints(): Assert\Collection;

    /**
     * @return array<string, string>
     */
    public function getMapping(): array
    {
        return $this->mapping;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function getTarget(): string
    {
        return $this->target;
    }

    /**
     * @param array<string, array<string, string>|string> $mappingArray
     *
     * @throws InvalidDatabaseConnectionException
     */
    protected function validateMapping(array $mappingArray, Assert\Composite $constraint): void
    {
        $mappingValidator = Validation::createValidator();
        $violations       = $mappingValidator->validate($mappingArray, $constraint);

        if (\count($violations) > 0) {
            $errorMessage = 'The configuration for the data transformation/mapping is invalid.';

            throw new InvalidDatabaseConnectionException($errorMessage);
        }
    }
}
