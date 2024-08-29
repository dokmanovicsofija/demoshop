<?php

namespace Application\Integration\Middleware;

use Application\Business\Interfaces\ServiceInterface\LoginServiceInterface;
use Application\Integration\Exceptions\RedirectToLoginException;
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

    public function __construct(private readonly LoginServiceInterface $loginService, private readonly CookieManager $cookieManager)
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
     * @throws RedirectToLoginException
     */
    public function handle(HttpRequest $request): void
    {
        $sessionManager = SessionManager::getInstance();

        if (!$sessionManager->get('loggedIn')) {
            $token = $this->cookieManager->getCookie('keepLoggedIn');

            if (!$token || !$this->loginService->validateToken($token)) {
                throw new RedirectToLoginException('User is not authenticated, redirecting to login.');
            }

            $sessionManager->set('loggedIn', true);
        }

        parent::handle($request);
    }
}