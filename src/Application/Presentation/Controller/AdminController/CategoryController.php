<?php

namespace Application\Presentation\Controller\AdminController;

use Application\Business\Interfaces\ServiceInterface\CategoryServiceInterface;
use Infrastructure\Request\HttpRequest;
use Infrastructure\Response\JsonResponse;

class CategoryController
{
    public function __construct(private CategoryServiceInterface $categoryService)
    {
    }

    public function getCategories(HttpRequest $request): JsonResponse
    {
        $categories = $this->categoryService->getAllCategories();
        return new JsonResponse($categories);
    }
}
