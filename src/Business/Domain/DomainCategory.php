<?php

namespace Business\Domain;

class DomainCategory
{
    public function __construct(
        private int     $id,
        private ?int    $parentId,
        private string  $code,
        private string  $title,
        private ?string $description)
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
}
