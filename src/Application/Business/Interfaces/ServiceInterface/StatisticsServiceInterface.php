<?php

namespace Application\Business\Interfaces\ServiceInterface;

interface StatisticsServiceInterface
{
    /**
     * Get the home page view count.
     *
     * @return int The number of times the home page has been viewed.
     */
    public function getHomePageViewCount(): int;
}