<?php

declare(strict_types=1);

namespace App\DTO\Output\WordPress;

class WordPressDTO
{
    private string $wxrVersion = '1.2';
    private string $generator  = 'https://github.com/var-lab-it/universal-data-transformer';

    /**
     * @var array<ItemDTO>
     */
    private array $items = [];

    public function getWxrVersion(): string
    {
        return $this->wxrVersion;
    }

    public function getGenerator(): string
    {
        return $this->generator;
    }

    /**
     * @return array<ItemDTO>
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function addItem(ItemDTO $item): self
    {
        if (!isset($this->items[$item->getId()])) {
            $this->items[$item->getId()] = $item;
        }

        return $this;
    }

    public function removeItem(ItemDTO $item): self
    {
        if (isset($this->items[$item->getId()])) {
            unset($this->items[$item->getId()]);
        }

        return $this;
    }
}
