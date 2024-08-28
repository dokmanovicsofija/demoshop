<?php

namespace Application\Data\SQLRepositories;

use Application\Business\Domain\DomainProduct;
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

    /**
     * Saves a new product to the database and returns its unique identifier.
     *
     * This method takes a `DomainProduct` object, maps its properties to a new `Product` model,
     * and saves it to the database. It returns the ID of the newly created product.
     *
     * @param DomainProduct $product The domain model representing the product to be saved.
     * @return int The unique identifier of the newly saved product.
     */
    public function save(DomainProduct $product): int
    {
        $newProduct = new Product();
        $newProduct->sku = $product->getSku();
        $newProduct->title = $product->getTitle();
        $newProduct->brand = $product->getBrand();
        $newProduct->category_id = $product->getCategoryId();
        $newProduct->price = $product->getPrice();
        $newProduct->short_description = $product->getShortDescription();
        $newProduct->description = $product->getDescription();
        $newProduct->image = $product->getImage();
        $newProduct->enabled = $product->isEnabled();
        $newProduct->featured = $product->isFeatured();
        $newProduct->save();
        return $newProduct->id;
    }

    /**
     * Finds a product by its SKU in the database.
     *
     * This method searches for a product in the database using the provided SKU.
     * It returns `true` if a product with the given SKU exists, otherwise `false`.
     *
     * @param string $sku The SKU of the product to search for.
     * @return bool `true` if the product exists, `false` otherwise.
     */
    public function findBySku(string $sku): bool
    {
        $productModel = Product::where('sku', $sku)->first();

        if ($productModel) {
            return true;
        }

        return false;
    }

    /**
     * Finds a domain product by its ID.
     *
     * This method retrieves a product from the database by its unique identifier
     * and returns it as a `DomainProduct` object.
     *
     * @param int $productId The unique identifier of the product to find.
     * @return DomainProduct|null The domain product if found, or null if not found.
     */
    public function findDomainProductById(int $productId): ?DomainProduct
    {
        $productModel = Product::find($productId);

        if ($productModel) {
            return new DomainProduct(
                $productModel->id,
                $productModel->category_id,
                $productModel->sku,
                $productModel->title,
                $productModel->brand,
                $productModel->price,
                $productModel->short_description,
                $productModel->description,
                $productModel->image,
                $productModel->enabled,
                $productModel->featured,
                0
            );
        }

        return null;
    }

    /**
     * Retrieves a filtered and paginated list of products.
     *
     * @param int $page The current page number for pagination.
     * @param string $sort The sort order for the product price ('asc' or 'desc').
     * @param int|null $filter The category ID to filter the products by (optional).
     * @param string|null $search The search query to filter products by title (optional).
     * @return array The filtered and paginated list of products as an array.
     */
    public function getFilteredAndPaginatedProducts(
        int $page,
        string $sort = 'asc',
        ?int $filter = null,
        ?string $search = null
    ): array {
        $query = Product::query();

        if ($filter) {
            $query->where('category_id', $filter);
        }

        if ($search) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        $query->orderBy('price', $sort);

        $productsPerPage = 3;

        $totalProducts = $query->count();

        $offset = ($page - 1) * $productsPerPage;

        $products = $query->skip($offset)->take($productsPerPage)->get();

        return [
            'products' => $products->toArray(),
            'total_pages' => ceil($totalProducts / $productsPerPage),
            'current_page' => $page
        ];
    }
}
