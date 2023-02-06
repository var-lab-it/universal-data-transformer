<?php

declare(strict_types=1);

namespace App\DataFetcher;

use App\Config\Configuration;

class AbstractDataFetcher
{
    public function __construct(
        private readonly Configuration $configuration,
    ) {
    }

    public static function getInstance(Configuration $configuration): self
    {
        $classFqcn = $configuration->getDatabaseConfiguration()->getFetcherClassFqcn();

        /** @var AbstractDataFetcher $instance */
        $instance = new $classFqcn($configuration);

        return $instance;
    }
}
