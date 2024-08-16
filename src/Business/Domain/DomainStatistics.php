<?php

namespace Business\Domain;

/**
 * Class DomainStatistics
 *
 * Represents the statistics domain model, specifically tracking the number of times the home page has been viewed.
 */
class DomainStatistics
{
    /**
     * DomainStatistics constructor.
     *
     * Initializes the DomainStatistics object with provided values for id and home view count.
     *
     * @param int $id The unique identifier of the statistics record.
     * @param int $homeViewCount The number of times the home page has been viewed.
     */
    public function __construct(
        private int $id,
        private int $homeViewCount)
    {
    }

    /**
     * Get the unique identifier of the statistics record.
     *
     * @return int The statistics record's id.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the number of times the home page has been viewed.
     *
     * @return int The home page view count.
     */
    public function getHomeViewCount(): int
    {
        return $this->homeViewCount;
    }
}
