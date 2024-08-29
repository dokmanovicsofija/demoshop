<?php

namespace Application\Business\Interfaces\RepositoryInterface;

use Application\Business\Domain\DomainAdmin;
use Application\Data\Entities\Admin;

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

    /**
     * Update the authentication token for a specific admin.
     *
     * This method updates the `token` field for the admin with the given ID.
     * It assumes that the admin with the provided ID already exists.
     *
     * @param int $adminId The ID of the admin whose token needs to be updated.
     * @param string $token The new token to set for the admin.
     * @return void
     */
    public function updateToken(int $adminId, string $token): void;

    /**
     * Finds an admin by their token.
     *
     * @param string $token The token to search for.
     * @return Admin|null Returns the admin if found, null otherwise.
     */
    public function findByToken(string $token): ?Admin;
}
