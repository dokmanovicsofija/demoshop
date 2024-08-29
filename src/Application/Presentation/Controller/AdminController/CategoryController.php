<?php

namespace Application\Presentation\Controller\AdminController;

use Application\Business\Domain\DomainCategory;
use Application\Business\Interfaces\ServiceInterface\CategoryServiceInterface;
use Infrastructure\Request\HttpRequest;
use Infrastructure\Response\JsonResponse;

readonly class CategoryController
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

        if (empty($title) || empty($code)) {
            throw new \InvalidArgumentException('Title and code are required.');
        }

        $category = new DomainCategory(0, $parentId, $code, $title, $description);
        $newCategoryId = $this->categoryService->createRootCategory($category);

        return new JsonResponse(['id' => $newCategoryId], 201);
    }

    /**
     * Updates the parent of an existing category.
     *
     * This method updates the parent category of the specified category. It uses the ID of the category
     * and the new parent ID from the request data to perform the update. If the update is successful, it
     * returns a success message. If an error occurs, it returns an error message.
     *
     * @param HttpRequest $request The incoming HTTP request containing the category ID and new parent ID.
     * @return JsonResponse The JSON response with a success message or an error message.
     */
    public function updateCategory(HttpRequest $request): JsonResponse
    {
        $data = $request->getParsedBody();
        $categoryId = (int)$data['id'];
        $parentId = $data['parent'] ?? null;

        $this->categoryService->updateCategoryParent($categoryId, $parentId);

        return new JsonResponse(['message' => 'Category updated successfully']);
    }

    /**
     * Deletes a category based on the provided ID.
     *
     * This method deletes the specified category by its ID. Before deletion, it checks if the category has
     * associated products. If it does, an error is returned. Otherwise, the category is deleted, and a success
     * message is returned.
     *
     * @param HttpRequest $request The incoming HTTP request containing the category ID to be deleted.
     * @return JsonResponse The JSON response with a success message or an error message.
     */
    public function deleteCategory(HttpRequest $request): JsonResponse
    {
        $data = $request->getParsedBody();
        $categoryId = (int)$data['id'];

        $this->categoryService->deleteCategory($categoryId);
        return new JsonResponse(['message' => 'Category deleted successfully']);

    }

    /**
     * Endpoint to retrieve all categories.
     *
     */
    public function getAllCategories(): JsonResponse
    {
        $categories = $this->categoryService->getAllCategories2();
        return new JsonResponse($categories);
    }
}