<?php

namespace Application\Business\Interfaces\RepositoryInterface;

interface CategoryRepositoryInterface
{
    /**
     * Get the total count of categories.
     *
     * @return int The number of categories.
     */
    public function getCategoryCount(): int;
}
