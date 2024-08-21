<?php

namespace Application\Presentation\Controller\AdminController;

use Application\Business\Interfaces\ServiceInterface\CategoryServiceInterface;
use Application\Business\Interfaces\ServiceInterface\ProductServiceInterface;
use Application\Business\Interfaces\ServiceInterface\StatisticsServiceInterface;
use Infrastructure\Request\HttpRequest;
use Infrastructure\Response\JsonResponse;

/**
 * Class DashboardController
 *
 * This controller handles operations related to the admin dashboard.
 */
class DashboardController
{
    /**
     * DashboardController constructor.
     *
     * Initializes the controller with the necessary services to gather statistics for the dashboard.
     *
     * @param ProductServiceInterface $productService Service for handling product-related operations.
     * @param CategoryServiceInterface $categoryService Service for handling category-related operations.
     * @param StatisticsServiceInterface $statisticsService Service for gathering statistical data.
     */
    public function __construct(
        private ProductServiceInterface    $productService,
        private CategoryServiceInterface   $categoryService,
        private StatisticsServiceInterface $statisticsService
    )
    {
    }

    /**
     * Gathers statistics for the admin dashboard.
     *
     * This method collects various statistics such as the number of products, categories,
     * homepage views, and the most viewed product. These data are then returned in a JSON response.
     *
     * @param HttpRequest $request The incoming request from the client.
     * @return JsonResponse A JSON response containing the dashboard data.
     */
    public function getDashboardStats(HttpRequest $request): JsonResponse
    {
        $productCount = $this->productService->getProductCount();
        $categoryCount = $this->categoryService->getCategoryCount();
        $homePageViewCount = $this->statisticsService->getHomePageViewCount();
        $mostViewedProduct = $this->productService->getMostViewedProduct();

        $data = [
            'productCount' => $productCount,
            'categoryCount' => $categoryCount,
            'homePageViewCount' => $homePageViewCount,
            'mostViewedProduct' => $mostViewedProduct['productName'],
            'mostViewedProductCount' => $mostViewedProduct['viewCount']
        ];

        return new JsonResponse($data);
    }
}