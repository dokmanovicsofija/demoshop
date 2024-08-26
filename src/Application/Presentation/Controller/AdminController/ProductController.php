<?php

namespace Application\Presentation\Controller\AdminController;

use Application\Business\Interfaces\ServiceInterface\ProductServiceInterface;
use Infrastructure\Request\HttpRequest;
use Infrastructure\Response\JsonResponse;

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
}