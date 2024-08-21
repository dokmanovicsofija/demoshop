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
}