<?php

namespace Application\Business\Services;

use Application\Business\Interfaces\RepositoryInterface\LoginRepositoryInterface;
use Application\Business\Interfaces\ServiceInterface\LoginServiceInterface;
use Application\Integration\Exceptions\AuthenticationException;
use Application\Integration\Utility\PathHelper;
use Infrastructure\Response\HtmlResponse;
use Infrastructure\Utility\CookieManager;
use Infrastructure\Utility\SessionManager;
use Random\RandomException;

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
     * @return void
     * @throws AuthenticationException
     * @throws RandomException
     */
    public function authenticate(string $username, string $password, bool $keepLoggedIn): void
    {
        $admin = $this->adminRepository->findByUsername($username);

        if ($admin && password_verify($password, $admin->getPassword())) {

            SessionManager::getInstance()->set('loggedIn', true);


            if ($keepLoggedIn) {
                $token = bin2hex(random_bytes(32));
                $admin->setToken($token);
                $this->adminRepository->updateToken($admin->getId(), $token);

                CookieManager::getInstance()->setCookie('keepLoggedIn', $token, time() + (86400 * 30));
            }

            return;
        }

        throw new AuthenticationException("Invalid username or password");
    }

    /**
     * Validates the provided token by checking if it matches any admin in the database.
     *
     * @param string $token The token to validate.
     * @return bool Returns true if the token is valid, false otherwise.
     */
    public function validateToken(string $token): bool
    {
        $admin = $this->adminRepository->findByToken($token);
        return $admin !== null;
    }
}