<?php

namespace Application\Business\Services;

use Application\Business\Interfaces\RepositoryInterface\StatisticsRepositoryInterface;
use Application\Business\Interfaces\ServiceInterface\StatisticsServiceInterface;
use Application\Data\Entities\Statistics;

class StatisticsService implements StatisticsServiceInterface
{
    public function __construct(private StatisticsRepositoryInterface $statisticsRepository)
    {
    }
    /**
     * Get the home page view count.
     *
     * @return int The number of times the home page has been viewed.
     */
    public function getHomePageViewCount(): int
    {
        return $this->statisticsRepository->getHomeViewCount();
    }
}
