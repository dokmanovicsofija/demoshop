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

    public function findSubcategories(int $categoryId): array;

    public function updateParent(int $categoryId, ?int $newParentId): void;

    public function findById(int $categoryId): ?Category;

    public function deleteCategory(int $categoryId): void;

    public function categoryHasProducts(int $categoryId): bool;
}
