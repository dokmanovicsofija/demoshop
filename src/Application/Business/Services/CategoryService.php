<?php

namespace Application\Business\Services;

use Application\Business\Domain\DomainCategory;
use Application\Business\Interfaces\RepositoryInterface\CategoryRepositoryInterface;
use Application\Business\Interfaces\ServiceInterface\CategoryServiceInterface;
use InvalidArgumentException;

class CategoryService implements CategoryServiceInterface
{
    public function __construct(private CategoryRepositoryInterface $categoryRepository)
    {
    }

    /**
     * Get the total count of categories.
     *
     * @return int The number of categories.
     */
    public function getCategoryCount(): int
    {
        return $this->categoryRepository->getCategoryCount();
    }

    /**
     * Retrieve all categories including their subcategories.
     *
     * @return array An array of categories with their subcategories.
     */
    public function getAllCategories(): array
    {
        return $this->categoryRepository->findAllWithSubcategories();
    }

    /**
     * Create a new root category.
     *
     * @param DomainCategory $category The domain category object containing category details.
     * @return int The ID of the newly created category.
     */
    public function createRootCategory(DomainCategory $category): int
    {
        if ($this->categoryRepository->findByCode($category->getCode())) {
            throw new \InvalidArgumentException('Category code must be unique.');
        }
        return $this->categoryRepository->addRootCategory($category);
    }

    /**
     * Update the parent of a given category.
     *
     * This method updates the parent category of a specified category. If the new parent category is also a subcategory
     * of the category being updated, it sets the subcategory's parent to null.
     *
     * @param int $categoryId The ID of the category to update.
     * @param int|null $parentId The ID of the new parent category, or null to make it a root category.
     * @return void
     * @throws \InvalidArgumentException If the category to be updated does not exist.
     */
    public function updateCategoryParent(int $categoryId, ?int $parentId): void
    {
        $category = $this->categoryRepository->findById($categoryId);
        if (!$category) {
            throw new InvalidArgumentException("Category not found.");
        }

        $subcategories = $this->categoryRepository->findSubcategories($categoryId);


        if (in_array($parentId, array_column($subcategories, 'id'))) {
            foreach ($subcategories as $subcategory) {
                if ($subcategory['id'] == $parentId) {
                    $this->categoryRepository->updateParent($subcategory['id'], null);
                }
            }
            $this->categoryRepository->updateParent($categoryId, $parentId);
        } else {
            $this->categoryRepository->updateParent($categoryId, $parentId);
        }
    }

    /**
     * Delete a category by its ID.
     *
     * This method removes a category from the system if it does not have associated products.
     * Throws an exception if the category has products.
     *
     * @param int $categoryId The ID of the category to be deleted.
     * @return void
     * @throws InvalidArgumentException If the category has associated products and cannot be deleted.
     */
    public function deleteCategory(int $categoryId): void
    {
        if ($this->categoryRepository->categoryHasProducts($categoryId)) {
            throw new InvalidArgumentException('Category having products can’t be deleted.');
        }

        $this->categoryRepository->deleteCategory($categoryId);
    }

    /**
     * Get all categories without subcategories.
     *
     * @return array
     */
    public function getAllCategories2(): array
    {
        return $this->categoryRepository->findAllCategories();
    }
}