<?php

declare(strict_types=1);

namespace App\DTO\Output\WordPress;

class WordPress
{
    private string $wxrVersion = '1.2';
    private string $generator  = 'https://github.com/var-lab-it/universal-data-transformer';

    /**
     * @var array<Item>
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
     * @return array<Item>
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function addItem(Item $item): self
    {
        if (!isset($this->items[$item->getId()])) {
            $this->items[$item->getId()] = $item;
        }

        return $this;
    }

    public function removeItem(Item $item): self
    {
        if (isset($this->items[$item->getId()])) {
            unset($this->items[$item->getId()]);
        }

        return $this;
    }
}
