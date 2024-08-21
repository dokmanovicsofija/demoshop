<?php

namespace Application\Business\Interfaces\ServiceInterface;

interface CategoryServiceInterface
{
    /**
     * Get the total count of categories.
     *
     * @return int The number of categories.
     */
    public function getCategoryCount(): int;

    public function getAllCategories(): array;
}