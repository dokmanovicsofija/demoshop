<?php

namespace Application\Business\Domain;

/**
 * Class DomainAdmin
 *
 * Represents an admin domain model with attributes such as id, username, password, and an optional token.
 */
class DomainAdmin
{
    /**
     * DomainAdmin constructor.
     *
     * Initializes the DomainAdmin object with provided id, username, password, and optional token.
     *
     * @param int $id The unique identifier of the admin.
     * @param string $username The username of the admin.
     * @param string $password The hashed password of the admin.
     * @param string|null $token An optional token associated with the admin (default is null).
     */
    public function __construct(
        private int $id,
        private string $username,
        private string $password,
        private ?string $token = null
    ) {
    }

    /**
     * Get the unique identifier of the admin.
     *
     * @return int The admin's id.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the username of the admin.
     *
     * @return string The admins username.
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Get the hashed password of the admin.
     *
     * @return string The admins hashed password.
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Get the optional token associated with the admin.
     *
     * @return string|null The admins token, or null if not set.
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }
}
