<?php

namespace Application\Integration\Middleware;

use Application\Integration\Exceptions\AuthorizationException;
use Infrastructure\Request\HttpRequest;

class AdminMiddleware extends AbstractMiddleware
{
    public function handle(HttpRequest $request): void
    {
//        $userId = SessionManager::getInstance()->get('admin');
        $keepLoggedIn = $_COOKIE['keepLoggedIn'] ?? 'false';

        if ($keepLoggedIn !== 'true') {
//            $response = new RedirectResponse('/admin');
//            $response->send();
//            exit;
            throw new AuthorizationException('You are not authorized to access this page.');
        }

        parent::handle($request);
    }
}