<?php

declare(strict_types=1);

namespace App\Tests\UnitTests\DTO\Output\WordPress;

use App\DTO\Output\WordPress\ItemDTO;
use PHPUnit\Framework\TestCase;

class ItemDTOTest extends TestCase
{
    public function testId(): void
    {
        $dto = new ItemDTO();
        $dto->setId('123xy');
        self::assertEquals('123xy', $dto->getId());
    }

    public function testTitle(): void
    {
        $dto = new ItemDTO();
        $dto->setTitle('this is a title');
        self::assertEquals('this is a title', $dto->getTitle());
    }

    public function testPubDate(): void
    {
        $dto = new ItemDTO();
        $dto->setPubDate('2023-02-04 13:49:11');
        self::assertEquals('2023-02-04 13:49:11', $dto->getPubDate());
    }

    public function testContent(): void
    {
        $dto = new ItemDTO();
        $dto->setContent('<html>content</html>');
        self::assertEquals('<html>content</html>', $dto->getContent());
    }

    public function testExcerpt(): void
    {
        $dto = new ItemDTO();
        $dto->setExcerpt('Excerpt');
        self::assertEquals('Excerpt', $dto->getExcerpt());
    }
}
