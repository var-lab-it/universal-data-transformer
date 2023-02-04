<?php

declare(strict_types=1);

namespace App\Config;

interface SectionConfigurationInterface
{
    public static function fromConfig(array $config): void;
}
