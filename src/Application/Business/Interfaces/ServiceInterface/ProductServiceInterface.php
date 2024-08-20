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
     * @return mixed The most viewed product details.
     */
    public function getMostViewedProduct();
}

