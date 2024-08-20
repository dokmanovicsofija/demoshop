<?php

namespace Application\Data\SQLRepositories;

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
}
