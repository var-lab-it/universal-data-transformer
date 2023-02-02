<?php

declare(strict_types=1);

namespace UnitTests;

use PHPUnit\Framework\TestCase;

class TestTrue extends TestCase
{
    public function testTrue(): void
    {
        /** @phpstan-ignore-next-line */
        self::assertTrue(true);
    }
}
