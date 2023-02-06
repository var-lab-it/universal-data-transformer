<?php

declare(strict_types=1);

namespace App\Config\Database;

use App\DataFetcher\PdoMySQLFetcher;

class PdoMySqlConnection extends AbstractDatabaseConfiguration
{
    public function getFetcherClassFqcn(): string
    {
        return PdoMySQLFetcher::class;
    }
}
