<?php

namespace Application\Business\Interfaces\ServiceInterface;

interface ProductServiceInterface
{
    /**
     * Get the total count of products.
     *
     * @return int The number of products.
     */
    public function getProductCount(): int;

    /**
     * Get the most viewed product.
     *
     */
    public function getMostViewedProduct(): ?array;

    /**
     * Fetches all products from the database.
     *
     */
    public function getAllProducts(): array;
}

