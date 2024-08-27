<?php

namespace Application\Business\Interfaces\RepositoryInterface;

use Application\Business\Domain\DomainProduct;

interface ProductRepositoryInterface
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
    public function findAll(): array;

    /**
     * Update the enable status of multiple products.
     *
     * @param array $productIds An array of product IDs to update.
     * @param bool $status The status to set (true for enable, false for disable).
     * @return void
     */
    public function updateStatus(array $productIds, bool $status): void;

    /**
     * Deletes a product by its ID.
     *
     * This method is responsible for deleting a product from the database based on the provided product ID.
     * Implementations of this method should handle the deletion logic, including any necessary error handling.
     *
     * @param int $productId The ID of the product to be deleted.
     * @return void
     */
    public function deleteProductById(int $productId): void;

    /**
     * Saves a new product to the database and returns its unique identifier.
     *
     * This method is responsible for persisting a `DomainProduct` object to the database.
     * Implementing classes should map the properties of the `DomainProduct` to a database model and save it.
     *
     * @param DomainProduct $product The domain model representing the product to be saved.
     * @return int The unique identifier of the newly saved product.
     */
    public function save(DomainProduct $product): int;

    /**
     * Finds a product by its SKU in the database.
     *
     * This method searches for a product in the database using the provided SKU.
     * It should return `true` if a product with the given SKU exists, otherwise `false`.
     *
     * @param string $sku The SKU of the product to search for.
     * @return bool `true` if the product exists, `false` otherwise.
     */
    public function findBySku(string $sku): bool;
}
