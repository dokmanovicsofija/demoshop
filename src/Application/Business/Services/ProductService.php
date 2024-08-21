<?php

namespace Application\Business\Services;

use Application\Business\Interfaces\RepositoryInterface\ProductRepositoryInterface;
use Application\Business\Interfaces\ServiceInterface\ProductServiceInterface;
use Application\Data\Entities\Product;

class ProductService implements ProductServiceInterface
{
    public function __construct(private ProductRepositoryInterface $productRepository)
    {
    }
    /**
     * Get the total count of products.
     *
     * @return int The number of products.
     */
    public function getProductCount(): int
    {
        return $this->productRepository->getProductCount();
    }

    /**
     * Get the most viewed product.
     *
     * @return Product|null The most viewed product details, or null if no product exists.
     */
    public function getMostViewedProduct(): ?array
    {
        return $this->productRepository->getMostViewedProduct();
    }
}
