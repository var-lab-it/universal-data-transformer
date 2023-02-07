<?php

declare(strict_types=1);

namespace App\DataTransformer;

use App\Config\Configuration;
use App\Exception\InvalidTransformerIdentifierException;

class AbstractDataTransformer
{
    private const TRANSFORMER_MAPPING = [
        'database.WordPress' => DatabaseToWordPressDataTransformer::class,
    ];

    public function __construct(
        private readonly Configuration $configuration,
    ) {
    }

    /**
     * @throws InvalidTransformerIdentifierException
     */
    public static function getInstance(Configuration $configuration): self
    {
        $transformerClassFqcn = $configuration->getTransformerIdentifier();

        if (!isset(self::TRANSFORMER_MAPPING[$transformerClassFqcn])) {
            throw new InvalidTransformerIdentifierException(
                "No transformer found for transformer id \"${transformerClassFqcn}\". Please check your configuration.",
            );
        }

        /** @var AbstractDataTransformer $instance */
        $instance = new $transformerClassFqcn($configuration);

        return $instance;
    }
}
