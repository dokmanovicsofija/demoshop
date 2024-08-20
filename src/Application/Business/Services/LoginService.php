<?php

namespace Application\Business\Services;

use Application\Business\Exceptions\AuthenticationException;
use Application\Business\Interfaces\RepositoryInterface\LoginRepositoryInterface;
use Application\Business\Interfaces\ServiceInterface\LoginServiceInterface;
use Infrastructure\Utility\SessionManager;

/**
 * Class LoginService
 *
 * Implements the login service that handles the authentication logic for admin users.
 */
class LoginService implements LoginServiceInterface
{
    /**
     * LoginService constructor.
     *
     * @param LoginRepositoryInterface $adminRepository The repository interface for admin data access.
     */
    public function __construct(private LoginRepositoryInterface $adminRepository)
    {
    }

    /**
     * Authenticate an admin user.
     *
     * This method verifies the provided username and password against the stored credentials.
     * If the authentication is successful, a session is started and the user's ID is stored.
     * Optionally, a "keep me logged in" feature is supported by setting a cookie.
     *
     * @param string $username The username of the admin attempting to log in.
     * @param string $password The password of the admin attempting to log in.
     * @param bool $keepLoggedIn A flag indicating whether the admin should remain logged in across sessions.
     * @return bool
     * @throws AuthenticationException
     */
    public function authenticate(string $username, string $password, bool $keepLoggedIn): bool
    {
        $admin = $this->adminRepository->findByUsername($username);

        if ($admin && password_verify($password, $admin->getPassword())) {

            SessionManager::getInstance()->set('admin', $admin->getId());

            if ($keepLoggedIn) {
                SessionManager::getInstance()->setCookie(session_name(), session_id(), time() + (86400 * 30), "/", "", false, true);
            }

            return true;
        } else {
            throw new AuthenticationException("Invalid username or password");
        }
    }
}