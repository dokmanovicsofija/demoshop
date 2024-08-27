<?php

namespace Application\Data\SQLRepositories;

use Application\Business\Domain\DomainAdmin;
use Application\Business\Interfaces\RepositoryInterface\LoginRepositoryInterface;
use Application\Data\Entities\Admin;

//use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * Class LoginRepository
 *
 * This class implements the LoginRepositoryInterface and provides methods to interact with the admin data stored in the database.
 * It uses Eloquent ORM to perform database operations.
 */
class LoginRepository implements LoginRepositoryInterface
{
    /**
     * Find an admin by their username.
     *
     * This method retrieves the admin data from the database based on the provided username.
     * If an admin with the specified username is found, it returns a DomainAdmin object.
     * Otherwise, it returns null.
     *
     * @param string $username The username of the admin to be retrieved.
     * @return DomainAdmin|null Returns a DomainAdmin object if the admin is found, or null if not.
     */
    public function findByUsername(string $username): ?DomainAdmin
    {
        $adminData = Admin::where('username', $username)->first();

        if (!$adminData) {
            return null;
        }

        return new DomainAdmin(
            $adminData->id,
            $adminData->username,
            $adminData->password,
            $adminData->token
        );
    }
}
