<?php

namespace Application\Integration\Middleware;

use Application\Integration\Exceptions\AuthorizationException;
use Infrastructure\Request\HttpRequest;
use Infrastructure\Response\RedirectResponse;

/**
 * Class AdminMiddleware
 *
 * This middleware ensures that only authenticated users can access admin routes.
 * If the user is not logged in, they are redirected to the login page.
 */
class AdminMiddleware extends AbstractMiddleware
{
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
        $keepLoggedIn = $_COOKIE['keepLoggedIn'] ?? 'false';

        if ($keepLoggedIn !== 'true') {
            $response = new RedirectResponse('/login');
            $response->send();
            exit;
        }

        parent::handle($request);
    }
}