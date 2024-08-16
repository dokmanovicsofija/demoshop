<?php

namespace Business\Interfaces\ServiceInterface;

use Business\Domain\DomainAdmin;

interface LoginServiceInterface
{
public function authenticate(string $username, string $password, bool $keepLoggedIn): array;
}
