<?php

namespace Application\Data\SQLRepositories;

use Application\Business\Interfaces\RepositoryInterface\ProductRepositoryInterface;
use Application\Data\Entities\Product;
use Exception;

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

    /**
     * Update the enable status for a list of products.
     *
     * This method updates the `enabled` status of the specified products in the `products` table.
     * The status is set to 1 (enabled) or 0 (disabled) based on the provided boolean value.
     *
     * @param array $productIds An array of product IDs to be updated.
     * @param bool $status The new status to set for the specified products (true for enabled, false for disabled).
     *
     * @return void
     */
    public function updateStatus(array $productIds, bool $status): void
    {
        $isEnabled = $status ? 1 : 0;

        Product::whereIn('id', $productIds)->update(['enabled' => $isEnabled]);
    }

    /**
     * Deletes a product from the database by its ID.
     *
     * This method uses Eloquent to find a product by its ID and then deletes it from the database.
     * If the product with the specified ID does not exist, an exception is thrown.
     *
     * @param int $productId The ID of the product to be deleted.
     * @return void
     * @throws Exception If the product with the given ID is not found.
     */
    public function deleteProductById(int $productId): void
    {
        $product = Product::find($productId);

        if ($product) {
            $product->delete();
        } else {
            throw new Exception("Product with ID {$productId} not found.");
        }
    }
}
