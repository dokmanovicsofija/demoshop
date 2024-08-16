<?php

namespace Business\Domain;

class DomainAdmin
{
    public function __construct(private int $id, private string $username, private string $password, private ?string $token = null)
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }
}
