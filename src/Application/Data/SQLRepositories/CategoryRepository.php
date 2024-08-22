<?php

namespace Application\Data\SQLRepositories;

use Application\Business\Domain\DomainCategory;
use Application\Business\Interfaces\RepositoryInterface\CategoryRepositoryInterface;
use Application\Data\Entities\Category;

class CategoryRepository implements CategoryRepositoryInterface
{
    /**
     * Get the total count of categories.
     *
     * @return int The number of categories.
     */
    public function getCategoryCount(): int
    {
        return Category::count();
    }

    /**
     * Retrieves all categories with their subcategories.
     *
     * This method fetches all categories that do not have a parent (root categories)
     * and includes their associated subcategories in the result.
     *
     * @return array An array of categories, each with its subcategories.
     */
//    public function findAllWithSubcategories(): array
//    {
//        $categories = Category::with('subcategories')->whereNull('parent_id')->get();
//
//        return $categories->toArray();
//    }

    public function findAllWithSubcategories(): array
    {
        $categories = Category::with('subcategories.products')->whereNull('parent_id')->get();
//        $categories = Category::with('subcategories.subcategories')->whereNull('parent_id')->get();

        return $categories->map(function ($category) {
            return $this->loadSubcategories($category);
        })->toArray();
    }

    private function loadSubcategories($category)
    {
        $category->subcategories = $category->subcategories->map(function ($subcategory) {
            return $this->loadSubcategories($subcategory);
        });

        return $category;
    }

    /**
     * Adds a new root category to the database.
     *
     * This method creates a new root category (i.e., a category with no parent)
     * based on the provided domain model and saves it to the database.
     *
     * @param DomainCategory $category The domain category object containing the details of the new category.
     * @return int The ID of the newly created root category.
     */
    public function addRootCategory(DomainCategory $category): int
    {
        $newCategory = new Category();
        $newCategory->title = $category->getTitle();
        $newCategory->code = $category->getCode();
        $newCategory->description = $category->getDescription();
        $newCategory->parent_id = $category->getParentId();
        $newCategory->save();

        return $newCategory->id;
    }

    public function findSubcategories(int $categoryId): array
    {
        return Category::where('parent_id', $categoryId)->get()->toArray();
    }

    public function updateParent(int $categoryId, ?int $newParentId): void
    {
        $category = Category::find($categoryId);
        if (!$category) {
            throw new \InvalidArgumentException('Category not found');
        }
        $category->parent_id = $newParentId;
        $category->save();
    }

    public function findById(int $categoryId): ?Category
    {
        return Category::find($categoryId);
    }

    public function deleteCategory(int $categoryId): void
    {
        $category = $this->findById($categoryId);
        if ($category && $category->subcategories) {
            foreach ($category->subcategories as $subcategory) {
                $subcategory->parent_id = $category->parent_id;
                $subcategory->save();
            }
        }

        $category->delete();
    }

    public function categoryHasProducts(int $categoryId): bool
    {
        $category = $this->findById($categoryId);
        return $category ? $category->products()->exists() : false;
    }
}
