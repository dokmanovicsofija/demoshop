<?php

namespace Application\Data\SQLRepositories;

use Application\Business\Interfaces\RepositoryInterface\StatisticsRepositoryInterface;
use Application\Data\Entities\Statistics;

class StatisticsRepository implements StatisticsRepositoryInterface
{
    /**
     * Get the home page view count.
     *
     * @return int The number of times the home page has been viewed.
     */
    public function getHomeViewCount(): int
    {
        $statistics = Statistics::first();
        return $statistics ? $statistics->home_view_count : 0;
    }
}