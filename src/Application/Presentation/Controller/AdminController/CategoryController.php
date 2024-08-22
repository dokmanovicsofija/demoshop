<?php

namespace Application\Presentation\Controller\AdminController;

use Application\Business\Domain\DomainCategory;
use Application\Business\Interfaces\ServiceInterface\CategoryServiceInterface;
use Infrastructure\Request\HttpRequest;
use Infrastructure\Response\JsonResponse;

class CategoryController
{
    /**
     * CategoryController constructor.
     *
     * @param CategoryServiceInterface $categoryService The service responsible for category operations.
     */
    public function __construct(private CategoryServiceInterface $categoryService)
    {
    }

    /**
     * Retrieves all categories and returns them as a JSON response.
     *
     * @param HttpRequest $request The incoming HTTP request.
     * @return JsonResponse The JSON response containing all categories.
     */
    public function getCategories(HttpRequest $request): JsonResponse
    {
        $categories = $this->categoryService->getAllCategories();
        return new JsonResponse($categories);
    }

    /**
     * Adds a new root category based on the provided data.
     *
     * @param HttpRequest $request The incoming HTTP request containing the category data.
     * @return JsonResponse The JSON response with the ID of the newly created category or an error message.
     */
    public function addCategory(HttpRequest $request): JsonResponse
    {
        $data = $request->getParsedBody();
        $title = $data['title'] ?? '';
        $code = $data['code'] ?? '';
        $description = $data['description'] ?? null;
        $parentId = isset($data['parent']) && $data['parent'] !== 'root' ? (int)$data['parent'] : null;

        try {
            $category = new DomainCategory(0, $parentId, $code, $title, $description);
            $newCategoryId = $this->categoryService->createRootCategory($category);

            return new JsonResponse(['id' => $newCategoryId], 201);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function updateCategory(HttpRequest $request): JsonResponse
    {
        $data = $request->getParsedBody();
        $categoryId = (int)$data['id'];
        $parentId = $data['parent'] ?? null;

        try {
            $this->categoryService->updateCategoryParent($categoryId, $parentId);

            return new JsonResponse(['message' => 'Category updated successfully']);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function deleteCategory(HttpRequest $request): JsonResponse
    {
        $data = $request->getParsedBody();
        $categoryId = (int)$data['id'];

        try {
            $this->categoryService->deleteCategory($categoryId);
            return new JsonResponse(['message' => 'Category deleted successfully']);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }

}
