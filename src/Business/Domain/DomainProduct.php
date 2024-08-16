<?php

namespace Business\Domain;

/**
 * Class DomainProduct
 *
 * Represents a product domain model with various attributes such as id, categoryId, SKU, title, brand, price, and more.
 */
class DomainProduct
{
    /**
     * DomainProduct constructor.
     *
     * Initializes the DomainProduct object with provided values for id, categoryId, SKU, title, brand, price, and more.
     *
     * @param int $id The unique identifier of the product.
     * @param int $categoryId The identifier of the category to which the product belongs.
     * @param string $sku The unique SKU of the product.
     * @param string $title The title or name of the product.
     * @param string $brand The brand of the product.
     * @param float $price The price of the product.
     * @param string|null $shortDescription A short description of the product (default is null).
     * @param string|null $description A detailed description of the product (default is null).
     * @param string|null $image The URL or path to the product image (default is null).
     * @param bool $enabled Indicates whether the product is enabled (default is true).
     * @param bool $featured Indicates whether the product is featured (default is false).
     * @param int $viewCount The number of times the product has been viewed (default is 0).
     */
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

    /**
     * Get the unique identifier of the product.
     *
     * @return int The product's id.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the identifier of the category to which the product belongs.
     *
     * @return int The category's id.
     */
    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    /**
     * Get the unique SKU of the product.
     *
     * @return string The product's SKU.
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * Get the title or name of the product.
     *
     * @return string The product's title.
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Get the brand of the product.
     *
     * @return string|null The product's brand, or null if not set.
     */
    public function getBrand(): ?string
    {
        return $this->brand;
    }

    /**
     * Get the price of the product.
     *
     * @return float The product's price.
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * Get the short description of the product.
     *
     * @return string|null The product's short description, or null if not set.
     */
    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    /**
     * Get the detailed description of the product.
     *
     * @return string|null The product's detailed description, or null if not set.
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Get the URL or path to the product image.
     *
     * @return string|null The product's image, or null if not set.
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * Check if the product is enabled (available for sale).
     *
     * @return bool True if the product is enabled, false otherwise.
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * Check if the product is featured (highlighted on the website).
     *
     * @return bool True if the product is featured, false otherwise.
     */
    public function isFeatured(): bool
    {
        return $this->featured;
    }

    /**
     * Get the number of times the product has been viewed.
     *
     * @return int The product's view count.
     */
    public function getViewCount(): int
    {
        return $this->viewCount;
    }
}
