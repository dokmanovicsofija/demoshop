<?php

namespace Business\Services;

use Business\Interfaces\RepositoryInterface\LoginRepositoryInterface;
use Business\Interfaces\ServiceInterface\LoginServiceInterface;
use Infrastructure\SessionManager;

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
     * @return array An associative array containing a success flag and a message.
     */
    public function authenticate(string $username, string $password, bool $keepLoggedIn): array
    {
        $admin = $this->adminRepository->findByUsername($username);

        if ($admin && password_verify($password, $admin->getPassword())) {

            $sessionManager = SessionManager::getInstance();
            $sessionManager->set('admin', $admin->getId());

            if ($keepLoggedIn) {
                $sessionManager->setCookie('admin', $admin->getId(), time() + (86400 * 30), "/", "", true, true);
            }

            return ['success' => true, 'message' => ''];
        } else {
            return ['success' => false, 'message' => 'Invalid username or password'];
        }
    }
}