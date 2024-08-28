<?php

namespace Application\Presentation\Controller\AdminController;

use Application\Business\Domain\DomainProduct;
use Application\Business\Interfaces\ServiceInterface\ProductServiceInterface;
use Infrastructure\Request\HttpRequest;
use Infrastructure\Response\JsonResponse;
use InvalidArgumentException;
use RuntimeException;

/**
 * Class ProductController
 *
 * This controller handles requests related to products in the admin section.
 * It interacts with the ProductService to perform operations and returns results as JSON responses.
 */
class ProductController
{
    /**
     * ProductController constructor.
     *
     * @param ProductServiceInterface $productService The service responsible for product-related operations.
     */
    public function __construct(private ProductServiceInterface $productService)
    {
    }

    /**
     * Retrieves all products and returns them as a JSON response.
     *
     * This method handles the incoming HTTP request, calls the ProductService to retrieve
     * all products, and returns them in JSON format. It is typically used in the admin section
     * to display a list of products.
     *
     * @param HttpRequest $request The incoming HTTP request object.
     * @return JsonResponse The JSON response containing the list of products.
     */
    public function getProducts(HttpRequest $request): JsonResponse
    {
        $products = $this->productService->getAllProducts();
        return new JsonResponse($products);
    }

    /**
     * Enable the specified products.
     *
     * This method retrieves the product IDs from the request, and then updates their status to enabled (true).
     * A JSON response with a success status is returned after the operation is completed.
     *
     * @param HttpRequest $request The HTTP request containing the product IDs to enable.
     * @return JsonResponse A JSON response indicating the success of the operation.
     */
    public function enableProducts(HttpRequest $request): JsonResponse
    {
        $productIds = $request->getParsedBody()['productIds'] ?? [];

        $this->productService->updateProductStatus($productIds, true);

        return new JsonResponse(['status' => 'success']);
    }

    /**
     * Disable the specified products.
     *
     * This method retrieves the product IDs from the request, and then updates their status to disabled (false).
     * A JSON response with a success status is returned after the operation is completed.
     *
     * @param HttpRequest $request The HTTP request containing the product IDs to disable.
     * @return JsonResponse A JSON response indicating the success of the operation.
     */
    public function disableProducts(HttpRequest $request): JsonResponse
    {
        $productIds = $request->getParsedBody()['productIds'] ?? [];

        $this->productService->updateProductStatus($productIds, false);

        return new JsonResponse(['status' => 'success']);
    }

    /**
     * Handles the deletion of a product.
     *
     * @param HttpRequest $request
     * @return JsonResponse
     */
    public function deleteProduct(HttpRequest $request): JsonResponse
    {
        $productIds = $request->getParsedBody()['ids'] ?? [];

        foreach ($productIds as $productId) {
            $this->productService->deleteProduct($productId);
        }

        return new JsonResponse(['status' => 'success', 'message' => 'Products deleted successfully']);
    }

    /**
     * Handles the process of storing a new product.
     *
     * @param HttpRequest $request The HTTP request containing product data and uploaded files.
     * @return JsonResponse The JSON response indicating the result of the operation.
     */
    public function storeProduct(HttpRequest $request): JsonResponse
    {
        $data = $request->bodyParams();
        $files = $request->getUploadedFiles();

        $sku = $data['sku'] ?? '';
        $title = $data['title'] ?? '';
        $brand = $data['brand'] ?? '';
        $categoryId = $data['category'] ?? '';
        $price = $data['price'] ?? 0;
        $shortDescription = $data['short_description'] ?? null;
        $description = $data['description'] ?? null;
        $enabled = isset($data['enabled']) && $data['enabled'] == 1;
        $featured = isset($data['featured']) && $data['featured'] == 1;
        $imageName = null;

        try {
            if (isset($files['image'])) {
                $imageName = $this->processImage($files['image']);
            }

            $productDomainModel = new DomainProduct(
                0,
                (int)$categoryId,
                $sku,
                $title,
                $brand,
                (float)$price,
                $shortDescription,
                $description,
                $imageName,
                $enabled,
                $featured,
                0
            );

            $id = $this->productService->createProduct($productDomainModel);

            return new JsonResponse(['status' => 'success', 'message' => 'Product added successfully']);
        } catch (InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        } catch (RuntimeException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Processes the uploaded image by validating, moving, and generating a unique file name.
     *
     * @param array $image The uploaded image file information from the $_FILES array.
     * @return string|null The name of the saved image file or null if no image was processed.
     * @throws InvalidArgumentException If there is an error with the uploaded file.
     * @throws RuntimeException If the file cannot be moved to the destination directory.
     */
    private function processImage(array $image): ?string
    {
        $tmpName = $image['tmp_name'];
        $error = $image['error'];

        if ($error !== UPLOAD_ERR_OK) {
            throw new InvalidArgumentException('Error uploading image.');
        }

        $uploadDir = realpath(__DIR__ . '/../../Public/uploads/');
        $imageName = uniqid() . basename($image['name']);
        $imagePath = $uploadDir . '/' . $imageName;

        if (!move_uploaded_file($tmpName, $imagePath)) {
            throw new RuntimeException('Failed to move uploaded file.');
        }

        return $imageName;
    }

    /**
     * Handles the request to list filtered and paginated products.
     *
     * This method processes the incoming HTTP request, extracting query parameters
     * for sorting, filtering, pagination, and search criteria. It then retrieves the
     * corresponding products using the product service and returns them as a JSON response.
     *
     * @param HttpRequest $request The incoming HTTP request containing query parameters.
     * @return JsonResponse A JSON response containing the filtered and paginated list of products.
     */
    public function listProducts(HttpRequest $request): JsonResponse
    {
        $sort = $request->getQueryParams('sort', 'ASC');
        $filter = (int)$request->getQueryParams('filter', null);
        $page = (int)$request->getQueryParams('page', 1);
        $search = $request->getQueryParams('search', null);

        $products = $this->productService->getFilteredAndPaginatedProducts($page, $sort, $filter, $search);

        return new JsonResponse($products);
    }
}