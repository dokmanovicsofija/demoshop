<?php

namespace Application\Business\Interfaces\ServiceInterface;

use Infrastructure\Response\HtmlResponse;

/**
 * Interface LoginServiceInterface
 *
 * Defines the contract for the login service, which handles the authentication process for admin users.
 */
interface LoginServiceInterface
{
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
     */
    public function authenticate(string $username, string $password, bool $keepLoggedIn): void;

    /**
     * Validates the provided token by checking if it matches any admin in the database.
     *
     * @param string $token The token to validate.
     * @return bool Returns true if the token is valid, false otherwise.
     */
    public function validateToken(string $token): bool;
}
