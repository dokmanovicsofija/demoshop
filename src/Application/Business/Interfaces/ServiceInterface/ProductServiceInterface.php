<?php

namespace Application\Business\Interfaces\ServiceInterface;

use Application\Business\Domain\DomainProduct;

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

    /**
     * Update the enable status of multiple products.
     *
     * @param array $productIds An array of product IDs to update.
     * @param bool $status The status to set (true for enable, false for disable).
     * @return void
     */
    public function updateProductStatus(array $productIds, bool $status): void;

    /**
     * Deletes a product by its ID.
     *
     * This method is responsible for initiating the process of deleting a product from the system
     * based on the provided product ID. Implementations of this method should ensure that the
     * product is properly removed from the database or other storage.
     *
     * @param int $productId The ID of the product to be deleted.
     * @return void
     */
    public function deleteProduct(int $productId): void;

    /**
     * Creates a new product in the system and returns its unique identifier.
     *
     * @param DomainProduct $product The domain model representing the product to be created.
     * @return int The unique identifier of the newly created product.
     */
    public function createProduct(DomainProduct $product): int;

    /**
     * Retrieves filtered, sorted, and paginated products.
     *
     * @param int $page The current page number for pagination.
     * @param string $sort The sort order for the products ('asc' or 'desc').
     * @param int|null $filter The category ID to filter products by. Optional.
     * @param string|null $search The search term to filter products by title. Optional.
     * @return array An array of products that match the specified criteria.
     */
    public function getFilteredAndPaginatedProducts(
        int $page,
        string $sort = 'asc',
        ?int $filter = null,
        ?string $search = null
    ): array;
}

