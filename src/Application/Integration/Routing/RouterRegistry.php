<?php

namespace Application\Integration\Routing;

use Application\Business\Interfaces\ServiceInterface\LoginServiceInterface;
use Application\Integration\Middleware\AdminMiddleware;
use Application\Presentation\Controller\AdminController\CategoryController;
use Application\Presentation\Controller\AdminController\DashboardController;
use Application\Presentation\Controller\AdminController\LoginController;
use Application\Presentation\Controller\AdminController\ProductController;
use Application\Presentation\Controller\FrontController\HomeController;
use Exception;
use Infrastructure\Utility\CookieManager;
use Infrastructure\Utility\ServiceRegistry;

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
        $loginService = ServiceRegistry::get(LoginServiceInterface::class);

        Router::getInstance()->addRoute(
            (new Route('GET', '/', HomeController::class, 'index'))
        );

        Router::getInstance()->addRoute(
            (new Route('GET', '/admin', LoginController::class, 'dashboard'))
                ->addMiddleware(new AdminMiddleware($loginService))
        );

        Router::getInstance()->addRoute(
            new Route('POST', '/admin', LoginController::class, 'login')
        );

        //Dashboard routes
        Router::getInstance()->addRoute(
            (new Route('GET', '/getDashboardStats',
                DashboardController::class, 'getDashboardStats'))
                ->addMiddleware(new AdminMiddleware($loginService))
        );

        Router::getInstance()->addRoute(
            (new Route('GET', '/admin/dashboard', LoginController::class, 'dashboard'))
                ->addMiddleware(new AdminMiddleware($loginService))
        );

        //Categories routes
        Router::getInstance()->addRoute(
            (new Route('GET', '/admin/categories', LoginController::class, 'dashboard'))
                ->addMiddleware(new AdminMiddleware($loginService))
        );

        Router::getInstance()->addRoute(
            (new Route('GET', '/getCategories', CategoryController::class, 'getCategories'))
                ->addMiddleware(new AdminMiddleware($loginService))
        );

        Router::getInstance()->addRoute(
            (new Route('POST', '/addCategory', CategoryController::class, 'addCategory'))
                ->addMiddleware(new AdminMiddleware($loginService))
        );

        Router::getInstance()->addRoute(
            (new Route('PUT', '/updateCategory',
                CategoryController::class, 'updateCategory'))
                ->addMiddleware(new AdminMiddleware($loginService))
        );

        Router::getInstance()->addRoute(
            (new Route('DELETE', '/deleteCategory',
                CategoryController::class, 'deleteCategory'))
                ->addMiddleware(new AdminMiddleware($loginService))
        );

        //Product routes
        Router::getInstance()->addRoute(
            (new Route('GET', '/admin/products', LoginController::class, 'dashboard'))
                ->addMiddleware(new AdminMiddleware($loginService))
        );

        Router::getInstance()->addRoute(
            (new Route('GET', '/getProducts', ProductController::class, 'getProducts'))
                ->addMiddleware(new AdminMiddleware($loginService))
        );

        //Enable/disable products
        Router::getInstance()->addRoute(
            (new Route('POST', '/enableProducts',
                ProductController::class, 'enableProducts'))
                ->addMiddleware(new AdminMiddleware($loginService))
        );

        Router::getInstance()->addRoute(
            (new Route('POST', '/disableProducts',
                ProductController::class, 'disableProducts'))
                ->addMiddleware(new AdminMiddleware($loginService))
        );

        Router::getInstance()->addRoute(
            (new Route('DELETE', '/deleteProduct',
                ProductController::class, 'deleteProduct'))
                ->addMiddleware(new AdminMiddleware($loginService))
        );

        Router::getInstance()->addRoute(
            (new Route('GET', '/getAllCategories',
                CategoryController::class, 'getAllCategories'))
                ->addMiddleware(new AdminMiddleware($loginService))
        );

        Router::getInstance()->addRoute(
            (new Route('POST', '/addProduct', ProductController::class, 'storeProduct'))
                ->addMiddleware(new AdminMiddleware($loginService))
        );

        Router::getInstance()->addRoute(
            (new Route('GET', '/listProducts', ProductController::class, 'listProducts'))
                ->addMiddleware(new AdminMiddleware($loginService))
        );
    }
}
