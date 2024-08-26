<?php

namespace Application\Business\Interfaces\RepositoryInterface;

use Application\Business\Domain\DomainCategory;
use Application\Data\Entities\Category;

interface CategoryRepositoryInterface
{
    /**
     * Get the total count of categories.
     *
     * @return int The number of categories.
     */
    public function getCategoryCount(): int;

    /**
     * Retrieves all categories with their subcategories.
     *
     * This method is responsible for fetching all root categories
     * and their associated subcategories.
     *
     * @return array An array of categories, each containing its subcategories.
     */
    public function findAllWithSubcategories(): array;

    /**
     * Adds a new root category to the database.
     *
     * This method creates a new root category (a category without a parent)
     * and returns the ID of the newly created category.
     *
     * @param DomainCategory $category The domain category object representing the new category.
     * @return int The ID of the newly created root category.
     */
    public function addRootCategory(DomainCategory $category): int;

    /**
     * Retrieves subcategories for a specific category.
     *
     * This method fetches all subcategories that belong to the specified category ID.
     *
     * @param int $categoryId The ID of the category whose subcategories are to be retrieved.
     * @return array An array of subcategories for the given category ID.
     */
    public function findSubcategories(int $categoryId): array;

    /**
     * Updates the parent category of a given category.
     *
     * This method changes the parent category of a specified category to a new parent category.
     * If the new parent ID is null, the category will be set as a root category.
     *
     * @param int $categoryId The ID of the category to be updated.
     * @param int|null $newParentId The ID of the new parent category, or null for a root category.
     * @return void
     */
    public function updateParent(int $categoryId, ?int $newParentId): void;

    /**
     * Finds a category by its ID.
     *
     * This method retrieves a category entity based on the specified category ID.
     *
     * @param int $categoryId The ID of the category to be retrieved.
     * @return Category|null The category entity if found, or null if not found.
     */
    public function findById(int $categoryId): ?Category;

    /**
     * Deletes a category from the database.
     *
     * This method removes a category based on the specified category ID.
     * It ensures that the category is deleted from the database.
     *
     * @param int $categoryId The ID of the category to be deleted.
     * @return void
     */
    public function deleteCategory(int $categoryId): void;

    /**
     * Checks if a category has associated products.
     *
     * This method determines whether the specified category contains any products.
     * It is used to ensure that categories with products are not deleted accidentally.
     *
     * @param int $categoryId The ID of the category to be checked.
     * @return bool True if the category has products, otherwise false.
     */
    public function categoryHasProducts(int $categoryId): bool;

    /**
     * Finds a category by its unique code.
     *
     * @param string $code The unique code of the category.
     * @return Category|null The category if found, null otherwise.
     */
    public function findByCode(string $code): ?Category;

    /**
     * Retrieve all categories from the database.
     *
     * @return array
     */
    public function findAllCategories(): array;
}
