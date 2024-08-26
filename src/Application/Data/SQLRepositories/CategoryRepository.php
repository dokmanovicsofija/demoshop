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
    public function findAllWithSubcategories(): array
    {
        $categories = Category::with('subcategories.products')->whereNull('parent_id')->get();

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

    /**
     * Retrieves all subcategories of a given category.
     *
     * This method fetches all categories that are direct subcategories of the specified category.
     *
     * @param int $categoryId The ID of the parent category for which subcategories are to be retrieved.
     * @return array An array of subcategories for the specified parent category.
     */
    public function findSubcategories(int $categoryId): array
    {
        return Category::where('parent_id', $categoryId)->get()->toArray();
    }

    /**
     * Updates the parent category of a given category.
     *
     * This method updates the parent category ID for a specified category. If the new parent category is
     * itself a subcategory of the current category, the method also updates the parent category of the subcategory.
     *
     * @param int $categoryId The ID of the category to update.
     * @param int|null $newParentId The ID of the new parent category, or null if the category should be a root category.
     * @return void
     */
    public function updateParent(int $categoryId, ?int $newParentId): void
    {
        $category = Category::find($categoryId);
        if (!$category) {
            throw new \InvalidArgumentException('Category not found');
        }
        $category->parent_id = $newParentId;
        $category->save();
    }

    /**
     * Finds a category by its ID.
     *
     * This method retrieves a category by its unique identifier. If no category with the given ID is found,
     * the method returns null.
     *
     * @param int $categoryId The ID of the category to retrieve.
     * @return Category|null The category entity if found, or null if not found.
     */
    public function findById(int $categoryId): ?Category
    {
        return Category::find($categoryId);
    }

    /**
     * Deletes a category by its ID.
     *
     * This method removes the category with the specified ID from the database. It does not perform any checks
     * before deletion, so it assumes that the category exists and can be safely removed.
     *
     * @param int $categoryId The ID of the category to delete.
     * @return void
     */
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

    /**
     * Checks if a category has associated products.
     *
     * This method determines whether a category has any products associated with it. It is used to prevent
     * deletion of categories that are still in use.
     *
     * @param int $categoryId The ID of the category to check.
     * @return bool True if the category has products, false otherwise.
     */
    public function categoryHasProducts(int $categoryId): bool
    {
        $category = $this->findById($categoryId);
        return $category ? $category->products()->exists() : false;
    }

    /**
     * Finds a category by its unique code.
     *
     * @param string $code The unique code of the category.
     * @return Category|null The category if found, null otherwise.
     */
    public function findByCode(string $code): ?Category
    {
        return Category::where('code', $code)->first();
    }

    /**
     * Retrieve all categories from the database.
     *
     * @return array
     */
    public function findAllCategories(): array
    {
        $categories = Category::all();

        return $categories->toArray();
    }
}
