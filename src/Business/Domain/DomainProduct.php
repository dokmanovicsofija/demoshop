<?php

namespace Business\Domain;

class DomainProduct
{
    public function __construct(
        private int     $id,
        private int     $categoryId,
        private string  $sku,
        private string  $title,
        private string  $brand,
        private float   $price,
        private ?string $shortDescription,
        private ?string $description,
        private ?string $image,
        private bool    $enabled,
        private bool    $featured,
        private int     $viewCount)
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function isFeatured(): bool
    {
        return $this->featured;
    }

    public function getViewCount(): int
    {
        return $this->viewCount;
    }
}
