<?php

namespace Application\Business\Services;

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
}