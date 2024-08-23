<?php

namespace Application\Presentation\Controller\FrontController;

use Application\Business\Interfaces\ServiceInterface\LoginServiceInterface;
use Application\Integration\Exceptions\AuthenticationException;
use Application\Integration\Utility\PathHelper;
use Infrastructure\Request\HttpRequest;
use Infrastructure\Response\HtmlResponse;
use Infrastructure\Utility\GlobalWrapper;

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
}