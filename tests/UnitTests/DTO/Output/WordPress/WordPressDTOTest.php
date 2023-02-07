<?php

declare(strict_types=1);

namespace App\Tests\UnitTests\DTO\Output\WordPress;

use App\DTO\Output\WordPress\ItemDTO;
use App\DTO\Output\WordPress\WordPressDTO;
use PHPUnit\Framework\TestCase;

class WordPressDTOTest extends TestCase
{
    public function testMetaProperties(): void
    {
        $dto = new WordPressDTO();
        self::assertEquals('1.2', $dto->getWxrVersion());
        self::assertEquals(
            'https://github.com/var-lab-it/universal-data-transformer',
            $dto->getGenerator(),
        );
    }

    public function testItems(): void
    {
        $dto = new WordPressDTO();
        self::assertCount(0, $dto->getItems());

        $item1id = \uniqid('', true);
        $item1   = new ItemDTO();
        $item1->setId($item1id);

        $dto->addItem($item1);

        self::assertCount(1, $dto->getItems());
        self::assertSame($item1, $dto->getItems()[$item1id]);

        $dto->addItem($item1);
        self::assertCount(1, $dto->getItems());
        self::assertSame($item1, $dto->getItems()[$item1id]);

        $item2id = \uniqid('', true);
        $item2   = new ItemDTO();
        $item2->setId($item2id);

        $dto->addItem($item2);

        self::assertCount(2, $dto->getItems());
        self::assertSame($item1, $dto->getItems()[$item1id]);
        self::assertSame($item2, $dto->getItems()[$item2id]);

        $dto->removeItem($item2);

        self::assertCount(1, $dto->getItems());
        self::assertSame($item1, $dto->getItems()[$item1id]);
        self::assertFalse(isset($dto->getItems()[$item2id]));
    }
}
