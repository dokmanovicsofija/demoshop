<?php

namespace Application\Business\Interfaces\RepositoryInterface;

interface StatisticsRepositoryInterface
{
    /**
     * Get the home page view count.
     *
     * @return int The number of times the home page has been viewed.
     */
    public function getHomeViewCount(): int;
}