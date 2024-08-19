<?php

namespace Middleware;

use Business\Exceptions\AuthorizationException;
use Infrastructure\Request\HttpRequest;
use Infrastructure\SessionManager;

class AdminMiddleware implements MiddlewareInterface
{
    /**
     * Handles the incoming request and checks if the user is authorized as an admin.
     *
     * This method retrieves the admin ID from the session. If the admin ID is not present,
     * it throws an AuthorizationException, preventing further access to the protected route.
     * If the admin ID is present, the request is passed to the next middleware or controller action.
     *
     * @param HttpRequest $request The incoming HTTP request.
     * @param callable $next The next middleware or controller action in the chain.
     * @throws AuthorizationException If the user is not authorized as an admin.
     */
    public function handle(HttpRequest $request, callable $next)
    {
        $adminId = SessionManager::getInstance()->get('admin');

        if (!$adminId) {
            throw new AuthorizationException('You are not authorized to access this page.');
        }

        return $next($request);
    }
}
