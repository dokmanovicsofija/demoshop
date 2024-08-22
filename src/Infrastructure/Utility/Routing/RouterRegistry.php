<?php

namespace Infrastructure\Utility\Routing;

use Application\Presentation\Controller\AdminController\CategoryController;
use Application\Presentation\Controller\AdminController\DashboardController;
use Application\Presentation\Controller\FrontController\LoginController;
use Exception;
use Infrastructure\Middleware\AdminMiddleware;

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
            new Route('GET', '/admin', LoginController::class, 'index')
        );

        Router::getInstance()->addRoute(
            new Route('POST', '/admin', LoginController::class, 'login')
        );

        Router::getInstance()->addRoute(
            (new Route('GET', '/admin/test', LoginController::class, 'test'))
                ->addMiddleware(new AdminMiddleware())
        );

        //Dashboard routes
        Router::getInstance()->addRoute(
            new Route('GET', '/getDashboardStats',
                DashboardController::class, 'getDashboardStats')
        );

        Router::getInstance()->addRoute(
            new Route('GET', '/admin/dashboard',
                LoginController::class, 'index')
        );

        Router::getInstance()->addRoute(
            new Route('GET', '/getCategories', CategoryController::class, 'getCategories')
        );

        Router::getInstance()->addRoute(
            new Route('GET', '/admin/categories',
                LoginController::class, 'index')
        );

        Router::getInstance()->addRoute(
            new Route('POST', '/addCategory', CategoryController::class, 'addCategory')
        );

        Router::getInstance()->addRoute(
            new Route('PUT', '/updateCategory', CategoryController::class, 'updateCategory')
        );

        Router::getInstance()->addRoute(
            new Route('DELETE', '/deleteCategory', CategoryController::class, 'deleteCategory')
        );
    }
}
