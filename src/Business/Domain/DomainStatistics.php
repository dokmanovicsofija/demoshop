<?php

namespace Business\Domain;

class DomainStatistics
{
    public function __construct(
        private int $id,
        private int $homeViewCount)
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getHomeViewCount(): int
    {
        return $this->homeViewCount;
    }
}
