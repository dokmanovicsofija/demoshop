<?php

namespace Application\Business\Interfaces\ServiceInterface;

use Application\Business\Domain\DomainCategory;

interface CategoryServiceInterface
{
    /**
     * Get the total count of categories.
     *
     * @return int The number of categories.
     */
    public function getCategoryCount(): int;

    /**
     * Retrieves all categories along with their subcategories.
     *
     * This method fetches all root categories and their associated subcategories
     * from the repository and returns them as an array.
     *
     * @return array An array of categories, each containing its subcategories.
     */
    public function getAllCategories(): array;

    /**
     * Creates a new root category.
     *
     * This method creates a new root category (a category without a parent)
     * in the system and returns the ID of the newly created category.
     *
     * @param DomainCategory $category The domain category object representing the new category.
     * @return int The ID of the newly created root category.
     */
    public function createRootCategory(DomainCategory $category): int;

    /**
     * Update the parent of a given category.
     *
     * @param int $categoryId The ID of the category to update.
     * @param int|null $parentId
     * @return void
     */
    public function updateCategoryParent(int $categoryId, ?int $parentId): void;

    /**
     * Deletes a category by its ID.
     *
     * This method removes a category from the system based on its ID.
     *
     * @param int $categoryId The ID of the category to be deleted.
     * @return void
     */
    public function deleteCategory(int $categoryId): void;

    /**
     * Get all categories without subcategories.
     *
     * @return array
     */
    public function getAllCategories2(): array;
}