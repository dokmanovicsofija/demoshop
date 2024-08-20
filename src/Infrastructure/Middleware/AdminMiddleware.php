<?php

namespace Infrastructure\Middleware;

use Application\Business\Exceptions\AuthorizationException;
use Infrastructure\Request\HttpRequest;
use Infrastructure\Response\RedirectResponse;
use Infrastructure\Utility\SessionManager;

class AdminMiddleware extends AbstractMiddleware
{
    public function handle(HttpRequest $request): void
    {
        $userId = SessionManager::getInstance()->get('admin');

        if (!$userId) {
//            $response = new RedirectResponse('/admin');
//            $response->send();
//            exit;
            throw new AuthorizationException('You are not authorized to access this page.');
        }

        parent::handle($request);
    }
}