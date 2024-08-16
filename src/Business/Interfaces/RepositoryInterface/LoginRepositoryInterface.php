<?php

namespace Business\Interfaces\RepositoryInterface;

use Business\Domain\DomainAdmin;

interface LoginRepositoryInterface
{
public function findByUsername(string $username): ?DomainAdmin;
public function create(string $username, string $hashedPassword): DomainAdmin;
}
