<?php

declare(strict_types=1);

namespace App\DTO\Output\WordPress;

class ItemDTO
{
    public const TYPE_POST = 'post';

    private string $id;
    private string $title;
    private string $pubDate;
    private string $content;
    private string $excerpt;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPubDate(): string
    {
        return $this->pubDate;
    }

    public function setPubDate(string $pubDate): self
    {
        $this->pubDate = $pubDate;

        return $this;
    }

    public function getExcerpt(): string
    {
        return $this->excerpt;
    }

    public function setExcerpt(string $excerpt): self
    {
        $this->excerpt = $excerpt;

        return $this;
    }
}
