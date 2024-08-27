<?php

namespace Application\Business\Interfaces\RepositoryInterface;

use Application\Business\Domain\DomainAdmin;

/**
 * Interface LoginRepositoryInterface
 *
 * Defines the contract for the login repository, which handles operations related to the `Admin` entity,
 * such as retrieving an admin user by their username.
 */
interface LoginRepositoryInterface
{
    /**
     * Find an admin by their username.
     *
     * This method searches for an admin in the database using the provided username.
     * If the admin is found, a `DomainAdmin` object is returned; otherwise, null is returned.
     *
     * @param string $username The username of the admin to find.
     * @return DomainAdmin|null The `DomainAdmin` object if found, or null if not found.
     */
    public function findByUsername(string $username): ?DomainAdmin;

    // TODO setTokenForAdmin, getAdminByTOken
}
