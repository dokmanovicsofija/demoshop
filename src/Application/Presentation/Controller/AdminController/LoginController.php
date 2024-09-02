<?php

namespace Application\Presentation\Controller\AdminController;

use Application\Business\Interfaces\ServiceInterface\LoginServiceInterface;
use Application\Integration\Exceptions\AuthenticationException;
use Application\Integration\Exceptions\ValidationException;
use Application\Integration\Utility\PathHelper;
use Infrastructure\Request\HttpRequest;
use Infrastructure\Response\HtmlResponse;

/**
 * Class LoginController
 *
 * Handles the login process and ensures that the user is redirected appropriately
 * based on their authentication status.
 */
class LoginController
{
    /**
     * LoginController constructor.
     *
     * @param LoginServiceInterface $loginService The service responsible for handling authentication logic.
     */
    public function __construct(private LoginServiceInterface $loginService)
    {
    }

    /**
     * Displays the dashboard view.
     *
     * This method returns an HTML response containing the dashboard view.
     *
     * @return HtmlResponse The response containing the dashboard view.
     */
    public function dashboard(): HtmlResponse
    {
        return HtmlResponse::fromView(PathHelper::view('dashboard.php'));
    }

    /**
     * Processes the login request and handles authentication.
     *
     * This method handles the POST request when the user submits the login form.
     * It attempts to authenticate the user using the provided credentials.
     * If successful, the user is redirected to the dashboard; otherwise,
     * an error message is displayed on the login form.
     *
     * @param HttpRequest $request The HTTP request containing the login data.
     * @return HtmlResponse The response containing either the dashboard or the login form with an error message.
     * @throws ValidationException
     */
    public function login(HttpRequest $request): HtmlResponse
    {
        $postData = $request->getAllPostData();
        $username = $postData['username'] ?? null;
        $password = $postData['password'] ?? null;
        $keepLoggedIn = isset($postData['keepLoggedIn']) && $postData['keepLoggedIn'] === 'on';

        $this->validateLoginData($username, $password);

        $this->loginService->authenticate($username, $password, $keepLoggedIn);

        return HtmlResponse::fromView(PathHelper::view('dashboard.php'));
    }

    /**
     * Validates the login data.
     *
     * This method checks the username and password provided by the user during login.
     * It ensures that the username is not empty, the password is not empty, and that the
     * password meets the minimum length requirement and matches the required pattern.
     * If any of these validations fail, it throws a `ValidationException`.
     *
     * @param string $username The username provided by the user.
     * @param string $password The password provided by the user.
     * @throws ValidationException if validation fails.
     */
    private function validateLoginData(string $username, string $password): void
    {
        if (empty($username)) {
            throw new ValidationException('Username is required.');
        }

        if (empty($password)) {
            throw new ValidationException('Password is required.');
        }

        if (strlen($password) < 8) {
            throw new ValidationException('Password must be at least 8 characters long.');
        }

        $pattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).+$/';
        if (!preg_match($pattern, $password)) {
            throw new ValidationException('Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.');
        }
    }
}