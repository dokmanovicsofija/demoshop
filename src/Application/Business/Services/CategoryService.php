<?php

namespace Application\Business\Services;

use Application\Business\Domain\DomainCategory;
use Application\Business\Interfaces\RepositoryInterface\CategoryRepositoryInterface;
use Application\Business\Interfaces\ServiceInterface\CategoryServiceInterface;
use Application\Data\Entities\Category;

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
        return $this->categoryRepository->addRootCategory($category);
    }

    public function updateCategoryParent(int $categoryId, ?int $parentId): void
    {
        $category = $this->categoryRepository->findById($categoryId);
        if (!$category) {
            throw new \InvalidArgumentException("Category not found.");
        }

        $subcategories = $this->categoryRepository->findSubcategories($categoryId);


        if (in_array($parentId, array_column($subcategories, 'id'))) {
            // Ako je parentId podkategorija trenutne kategorije, zamenite uloge
            foreach ($subcategories as $subcategory) {
                if ($subcategory['id'] == $parentId) {
                    // Postavite parentId podkategoriji kao root (null)
                    $this->categoryRepository->updateParent($subcategory['id'], null);
                }
            }
            // Postavite trenutnu kategoriju kao podkategoriju nove root kategorije
            $this->categoryRepository->updateParent($categoryId, $parentId);
        } else {
            // Ako nije, samo ažurirajte parentId
            $this->categoryRepository->updateParent($categoryId, $parentId);
        }
    }

    private function isCyclic(Category $category, Category $newParentCategory): bool
    {
        $currentCategory = $newParentCategory;
        while ($currentCategory) {
            if ($currentCategory->id === $category->id) {
                return true;
            }
            $currentCategory = $currentCategory->parent;
        }
        return false;
    }

    private function resolveCycle(Category $category, Category $newParentCategory): void
    {
        $category->parent_id = null;
        $newParentCategory->parent_id = null;

        $this->categoryRepository->save($category);
        $this->categoryRepository->save($newParentCategory);
    }
}