<?php

namespace Application\Integration\Middleware;

use Application\Business\Interfaces\ServiceInterface\LoginServiceInterface;
use Infrastructure\Request\HttpRequest;
use Infrastructure\Response\RedirectResponse;
use Infrastructure\Utility\CookieManager;
use Infrastructure\Utility\SessionManager;

/**
 * Class AdminMiddleware
 *
 * This middleware ensures that only authenticated users can access admin routes.
 * If the user is not logged in, they are redirected to the login page.
 */
class AdminMiddleware extends AbstractMiddleware
{

    public function __construct(private LoginServiceInterface $loginService, private CookieManager $cookieManager)
    {
    }

    /**
     * Handles the incoming HTTP request.
     *
     * This method checks if the user is authenticated by looking for the 'keepLoggedIn' cookie.
     * If the cookie is not set to 'true', the user is redirected to the login page.
     * If the user is authenticated, the request is passed to the next middleware or controller.
     *
     * @param HttpRequest $request The incoming HTTP request.
     * @return void
     */
    public function handle(HttpRequest $request): void
    {
        $sessionManager = SessionManager::getInstance();

        if (!$sessionManager->get('loggedIn')) {
            $token = $this->cookieManager->getCookie('keepLoggedIn');

            if (!$token || !$this->loginService->validateToken($token)) {
                $response = new RedirectResponse('/login');
                $response->send();
                return;
            }
            $sessionManager->set('loggedIn', true);
        }

        parent::handle($request);
    }
}