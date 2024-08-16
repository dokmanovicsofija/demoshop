<?php

namespace Infrastructure\Routing;

use Exception;
use Infrastructure\ServiceRegistry;
use Presentation\Controller\FrontController\LoginController;

/**
 * Class RouterRegistry
 *
 * This class is responsible for registering all the routes in the application.
 * It interacts with the Router class to ensure that all routes are properly defined and accessible.
 */
class RouterRegistry
{
    /**
     * Registers all the routes for the application.
     *
     * This method retrieves the Router instance from the ServiceRegistry and registers routes
     * by associating them with HTTP methods, URLs, controllers, and actions.
     *
     * @return void
     * @throws Exception If there is an issue retrieving the Router instance from the ServiceRegistry.
     *
     */
    public static function registerRoutes(): void
    {
        Router::getInstance()->addRoute(
            new Route('GET', '/admin', LoginController::class, 'showLoginForm')
        );

        Router::getInstance()->addRoute(
            new Route('POST', '/admin', LoginController::class, 'processLogin')
        );
    }
}
