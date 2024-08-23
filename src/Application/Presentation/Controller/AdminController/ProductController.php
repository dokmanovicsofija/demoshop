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
}