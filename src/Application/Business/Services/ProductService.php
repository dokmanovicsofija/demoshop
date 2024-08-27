<?php

namespace Application\Business\Services;

use Application\Business\Interfaces\RepositoryInterface\CategoryRepositoryInterface;
use Application\Business\Interfaces\RepositoryInterface\ProductRepositoryInterface;
use Application\Business\Interfaces\ServiceInterface\ProductServiceInterface;
use Application\Data\Entities\Product;
use Application\Business\Domain\DomainProduct;
use InvalidArgumentException;

readonly class ProductService implements ProductServiceInterface
{
    /**
     * @param ProductRepositoryInterface $productRepository
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private CategoryRepositoryInterface $categoryRepository
    ) {
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
        $product = $this->productRepository->findDomainProductById($productId);

        if ($product) {
            $imagePath = __DIR__ . '/../../Presentation/Public/uploads/' . $product->getImage();

            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            $this->productRepository->deleteProductById($productId);
        }
    }

    /**
     * Creates a new product in the system.
     *
     * This method first checks if a product with the same SKU already exists in the repository.
     * If such a product exists, it throws an `InvalidArgumentException`. Otherwise, it saves the new product
     * to the repository and returns the unique identifier of the newly created product.
     *
     * @param DomainProduct $product The domain model representing the product to be created.
     * @return int The unique identifier of the newly created product.
     * @throws InvalidArgumentException If a product with the same SKU already exists.
     */
    public function createProduct(DomainProduct $product): int
    {
        if ($this->productRepository->findBySku($product->getSku())) {
            throw new InvalidArgumentException('A product with the same SKU already exists.');
        }

        return $this->productRepository->save($product);
    }
}
