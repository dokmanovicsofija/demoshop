<?php

namespace Application\Business\Services;

use Application\Business\Interfaces\RepositoryInterface\CategoryRepositoryInterface;
use Application\Business\Interfaces\RepositoryInterface\ProductRepositoryInterface;
use Application\Business\Interfaces\ServiceInterface\ProductServiceInterface;
use Application\Data\Entities\Product;

class ProductService implements ProductServiceInterface
{
    public function __construct(private ProductRepositoryInterface  $productRepository,
                                private CategoryRepositoryInterface $categoryRepository)
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

    /**
     * Retrieve all products with their associated category names.
     *
     * This method fetches all products from the repository and, for each product,
     * retrieves the associated category name based on the `category_id`.
     * If the category is not found, 'Unknown' is assigned as the category name.
     *
     * @return array An array of products, each with its associated category name.
     */
    public function getAllProducts(): array
    {
        $products = $this->productRepository->findAll();

        foreach ($products as &$product) {
            $category = $this->categoryRepository->findById($product['category_id']);
            $product['category_name'] = $category ? $category->title : 'Unknown';
        }

        return $products;
    }

    /**
     * Update the status of multiple products.
     *
     * This method takes an array of product IDs and a status flag (true for enable, false for disable),
     * and updates the status of each specified product in the repository.
     *
     * @param array $productIds An array of product IDs to update.
     * @param bool $status The status to set for the specified products. True to enable, false to disable.
     * @return void
     */
    public function updateProductStatus(array $productIds, bool $status): void
    {
        $this->productRepository->updateStatus($productIds, $status);
    }

    /**
     * Deletes a product by its ID.
     *
     * This method delegates the deletion of a product to the repository layer.
     * It calls the `deleteProductById` method of the `ProductRepository` to remove the product
     * from the database. The repository is responsible for executing the actual deletion logic.
     *
     * @param int $productId The ID of the product to be deleted.
     * @return void
     */
    public function deleteProduct(int $productId): void
    {
        $this->productRepository->deleteProductById($productId);
    }
}
