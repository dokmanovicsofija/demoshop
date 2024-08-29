<?php

namespace Application\Presentation\Controller\AdminController;

use Application\Business\Interfaces\ServiceInterface\LoginServiceInterface;
use Application\Integration\Exceptions\AuthenticationException;
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
     * Displays the login form.
     *
     * This method returns an HTML response containing the login form.
     * There are no authentication checks within this method, as it's assumed
     * that protection is handled by middleware or other logic before this method is accessed.
     *
     * @return HtmlResponse The response containing the login form view.
     */
    public function index(): HtmlResponse
    {
        return HtmlResponse::fromView(PathHelper::view('login.php'));
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
     */
    public function login(HttpRequest $request): HtmlResponse
    {
        $postData = $request->getAllPostData();
        $username = $postData['username'] ?? null;
        $password = $postData['password'] ?? null;
        $keepLoggedIn = isset($postData['keepLoggedIn']) && $postData['keepLoggedIn'] === 'on';

        $validationResponse = $this->validateLoginData($username, $password);
        if ($validationResponse !== null) {
            return $validationResponse;
        }

        $this->loginService->authenticate($username, $password, $keepLoggedIn);

        return HtmlResponse::fromView(PathHelper::view('dashboard.php'));
    }

    /**
     * Validates the login data.
     *
     * This method checks the username and password provided by the user during login.
     * It ensures that the username is not empty, the password is not empty, and that the
     * password meets the minimum length requirement. If any of these validations fail,
     * it returns an `HtmlResponse` that renders the login page with the appropriate error message.
     * If all validations pass, it returns `null`, allowing the login process to proceed.
     *
     * @param string $username The username provided by the user.
     * @param string $password The password provided by the user.
     * @return HtmlResponse|null Returns an `HtmlResponse` with an error message if validation fails, or `null` if validation passes.
     */
    private function validateLoginData(string $username, string $password): ?HtmlResponse
    {
        if (empty($username)) {
            return HtmlResponse::fromView(PathHelper::view('login.php'), [
                'errorMessage' => 'Username is required.'
            ]);
        }

        if (empty($password)) {
            return HtmlResponse::fromView(PathHelper::view('login.php'), [
                'errorMessage' => 'Password is required.'
            ]);
        }

        if (strlen($password) < 5) {
            return HtmlResponse::fromView(PathHelper::view('login.php'), [
                'errorMessage' => 'Password must be at least 5 characters long.'
            ]);
        }

        return null;
    }
}