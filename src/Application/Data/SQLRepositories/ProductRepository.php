<?php

namespace Application\Data\SQLRepositories;

use Application\Business\Interfaces\RepositoryInterface\ProductRepositoryInterface;
use Application\Data\Entities\Product;

class ProductRepository implements ProductRepositoryInterface
{
    /**
     * Get the total count of products.
     *
     * @return int The number of products.
     */
    public function getProductCount(): int
    {
        return Product::count();
    }

    /**
     * Get the most viewed product.
     *
     * @return Product|null The most viewed product details, or null if no product exists.
     */
    public function getMostViewedProduct(): ?array
    {
        return Product::orderBy('view_count', 'desc')
            ->first(['title as productName', 'view_count as viewCount'])
            ->toArray();
    }

    /**
     * Retrieve all products from the database.
     *
     * This method fetches all products from the `products` table and returns them as an array.
     * Each product is represented as an associative array of its attributes.
     *
     * @return array An array of all products in the database.
     */
    public function findAll(): array
    {
        return Product::all()->toArray();
    }
}
