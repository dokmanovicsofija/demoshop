<?php

namespace Business\Domain;

/**
 * Class DomainCategory
 *
 * Represents a category domain model with attributes such as id, parentId, code, title, and an optional description.
 */
class DomainCategory
{
    /**
     * DomainCategory constructor.
     *
     * Initializes the DomainCategory object with provided id, parentId, code, title, and an optional description.
     *
     * @param int $id The unique identifier of the category.
     * @param int|null $parentId The identifier of the parent category, if applicable (default is null).
     * @param string $code The unique code of the category.
     * @param string $title The title of the category.
     * @param string|null $description The optional description of the category (default is null).
     */
    public function __construct(
        private int     $id,
        private ?int    $parentId,
        private string  $code,
        private string  $title,
        private ?string $description)
    {
    }

    /**
     * Get the unique identifier of the category.
     *
     * @return int The category's id.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the identifier of the parent category.
     *
     * @return int|null The parent category's id, or null if not applicable.
     */
    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    /**
     * Get the unique code of the category.
     *
     * @return string The category's code.
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Get the title of the category.
     *
     * @return string The category's title.
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Get the optional description of the category.
     *
     * @return string|null The category's description, or null if not set.
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }
}
