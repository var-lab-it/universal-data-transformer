<?php

declare(strict_types=1);

namespace App\Config\Transformation;

use App\Config\SectionConfigurationInterface;
use App\Exception\InvalidDatabaseConnectionException;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;

abstract class AbstractTransformationConfiguration implements SectionConfigurationInterface
{
    public const MAPPING_TARGET_WORDPRESS = 'WordPress';

    public const SUPPORTED_MAPPINGS = [
        self::MAPPING_TARGET_WORDPRESS => WordPressTransformationConfiguration::class,
    ];

    public function __construct(
        private readonly string $source,
        private readonly string $target,
        private readonly array $mapping,
    ) {
    }

    /**
     * @throws InvalidDatabaseConnectionException
     */
    public static function fromConfig(array $config): self
    {
        $constraint = new Assert\Collection([
            'source'  => new Assert\NotBlank([], 'The source must not be empty.'),
            'target'  => new Assert\NotBlank([], 'The target must not be empty.'),
            'mapping' => self::getMappingValidationConstraints(),
        ]);

        $validator = Validation::createValidator();

        $violations = $validator->validate($config, $constraint);

        if (\count($violations) > 0) {
            $errorMessage = 'The configuration for the data transformation/mapping is invalid.';

            throw new InvalidDatabaseConnectionException($errorMessage);
        }

        $mappingTarget = $config['target'];

        $classFQCN = self::SUPPORTED_MAPPINGS[$mappingTarget];

        return new $classFQCN(
            source: $config['source'],
            target: $config['target'],
            mapping: $config['mapping'],
        );
    }

    public static function getMappingValidationConstraints(): Assert\Collection
    {
        return new Assert\Collection([]);
    }

    /**
     * @return array
     */
    abstract public function getMapping(): array;

    public function getSource(): string
    {
        return $this->source;
    }

    public function getTarget(): string
    {
        return $this->target;
    }
}
