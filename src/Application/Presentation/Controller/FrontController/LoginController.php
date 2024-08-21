<?php

namespace Application\Presentation\Controller\FrontController;

use Application\Business\Exceptions\AuthenticationException;
use Application\Business\Interfaces\ServiceInterface\LoginServiceInterface;
use Infrastructure\Request\HttpRequest;
use Infrastructure\Response\HtmlResponse;
use Infrastructure\Utility\GlobalWrapper;
use Infrastructure\Utility\PathHelper;
use Infrastructure\Utility\SessionManager;

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

    public function test(HttpRequest $request): HtmlResponse
    {
        return HtmlResponse::fromView(PathHelper::view('dashboard.php'));
    }

    /**
     * Displays the login form or redirects to the dashboard if the user is already logged in.
     *
     * This method checks if the admin session is active. If the admin is already logged in,
     * it redirects to the dashboard. Otherwise, it displays the login form.
     *
     * @return HtmlResponse The response containing either the login form or the dashboard view.
     */
    public function index(): HtmlResponse
    {
        if ($this->checkAccess()) {
            return HtmlResponse::fromView(PathHelper::view('dashboard.php'));
        }

        return HtmlResponse::fromView(PathHelper::view('login.php'));
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
        $username = $request->post('username');
        $password = $request->post('password');
        $keepLoggedIn = $request->post('keepLoggedIn') === 'on';

        try {
            $this->loginService->authenticate($username, $password, $keepLoggedIn);
            return HtmlResponse::fromView(PathHelper::view('dashboard.php'));
        } catch (AuthenticationException $e) {
            return HtmlResponse::fromView(PathHelper::view('login.php'), [
                'errorMessage' => $e->getMessage()
            ]);
        }
    }

    /**
     * Checks if the user has access based on the 'keepLoggedIn' cookie.
     *
     * This method uses the `GlobalWrapper` class to access the 'keepLoggedIn' cookie.
     * If the value of this cookie is set to 'true', the user is considered logged in
     * and access is granted. Otherwise, access is denied.
     *
     * @return bool Returns true if the user selected 'keep me logged in', otherwise returns false.
     */

    public function checkAccess(): bool
    {
        $keepLoggedIn = GlobalWrapper::getCookie('keepLoggedIn') ?? 'false';

        if ($keepLoggedIn === 'true') {
            return true;
        }

        return false;
    }
}