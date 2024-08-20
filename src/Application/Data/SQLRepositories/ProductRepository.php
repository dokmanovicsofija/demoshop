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
    public function getMostViewedProduct(): ?Product
    {
        return Product::orderBy('view_count', 'desc')->first();
    }
}
