<?php

namespace Application\Data\SQLRepositories;

use Application\Business\Domain\DomainAdmin;
use Application\Business\Interfaces\RepositoryInterface\LoginRepositoryInterface;
use Application\Data\Entities\Admin;
use Exception;

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

    /**
     * Update the token for a specific admin in the database.
     *
     * This method updates the token field for the admin with the given ID.
     *
     * @param int $adminId The ID of the admin whose token is to be updated.
     * @param string $token The new token to be set.
     * @return void
     * @throws Exception
     */
    public function updateToken(int $adminId, string $token): void
    {
        $admin = Admin::find($adminId);

        $admin->token = $token;
        $admin->save();
    }

    /**
     * Finds an admin by their token.
     *
     * @param string $token The token to search for.
     * @return Admin|null Returns the admin if found, null otherwise.
     */
    public function findByToken(string $token): ?Admin
    {
        return Admin::where('token', $token)->first();
    }
}
