<?php

namespace Infrastructure;

use Application\Business\Interfaces\RepositoryInterface\CategoryRepositoryInterface;
use Application\Business\Interfaces\RepositoryInterface\LoginRepositoryInterface;
use Application\Business\Interfaces\RepositoryInterface\ProductRepositoryInterface;
use Application\Business\Interfaces\RepositoryInterface\StatisticsRepositoryInterface;
use Application\Business\Interfaces\ServiceInterface\CategoryServiceInterface;
use Application\Business\Interfaces\ServiceInterface\LoginServiceInterface;
use Application\Business\Interfaces\ServiceInterface\ProductServiceInterface;
use Application\Business\Interfaces\ServiceInterface\StatisticsServiceInterface;
use Application\Business\Services\CategoryService;
use Application\Business\Services\LoginService;
use Application\Business\Services\ProductService;
use Application\Business\Services\StatisticsService;
use Application\Data\SQLRepositories\CategoryRepository;
use Application\Data\SQLRepositories\LoginRepository;
use Application\Data\SQLRepositories\ProductRepository;
use Application\Data\SQLRepositories\StatisticsRepository;
use Application\Integration\Routing\Router;
use Application\Presentation\Controller\AdminController\CategoryController;
use Application\Presentation\Controller\AdminController\DashboardController;
use Application\Presentation\Controller\AdminController\ProductController;
use Application\Presentation\Controller\FrontController\LoginController;
use Exception;
use Infrastructure\Utility\ServiceRegistry;

/**
 * Class Bootstrap
 *
 * This class is responsible for initializing and registering all necessary components of the application.
 * It registers repositories, services, controllers, and routes to ensure that the application is properly set up.
 */
class Bootstrap
{
    /**
     * Initializes the application by registering all necessary components.
     *
     * This method orchestrates the initialization process by calling methods that register repositories, services,
     * controllers. It should be called at the start of the application.
     *
     * @throws Exception If there is an issue during the initialization process.
     */
    public static function initialize(): void
    {
        self::registerRepos();
        self::registerServices();
        self::registerControllers();
    }

    /**
     * Registers repository instances with the service registry.
     *
     * This method is used to register repositories that handle data storage and retrieval.
     * It should be implemented to register all repositories used in the application.
     *
     * @return void
     */
    protected static function registerRepos(): void
    {
        ServiceRegistry::register(LoginRepositoryInterface::class, new LoginRepository());
        ServiceRegistry::register(ProductRepositoryInterface::class, new ProductRepository());
        ServiceRegistry::register(CategoryRepositoryInterface::class, new CategoryRepository());
        ServiceRegistry::register(StatisticsRepositoryInterface::class, new StatisticsRepository());
    }

    /**
     * Registers service instances with the service registry.
     *
     * This method registers services that contain business logic and other operations.
     * It ensures that all services are available via the service registry.
     *
     * @return void
     * @throws Exception
     */
    protected static function registerServices(): void
    {
        ServiceRegistry::getInstance()->register(Router::class, Router::getInstance());
        ServiceRegistry::register(LoginServiceInterface::class, new LoginService(
            ServiceRegistry::get(LoginRepositoryInterface::class),
        ));
        ServiceRegistry::register(ProductServiceInterface::class, new ProductService(
            ServiceRegistry::get(ProductRepositoryInterface::class),
            ServiceRegistry::get(CategoryRepositoryInterface::class)
        ));
        ServiceRegistry::register(CategoryServiceInterface::class, new CategoryService(
            ServiceRegistry::get(CategoryRepositoryInterface::class),
        ));
        ServiceRegistry::register(StatisticsServiceInterface::class, new StatisticsService(
            ServiceRegistry::get(StatisticsRepositoryInterface::class),
        ));
    }

    /**
     * Registers controller instances with the service registry.
     *
     * This method registers controllers, which handle HTTP requests and responses.
     * It ensures that controllers are properly initialized and available via the service registry.
     *
     * @return void
     * @throws Exception
     */
    protected static function registerControllers(): void
    {
        ServiceRegistry::register(LoginController::class, new LoginController(
            ServiceRegistry::get(LoginServiceInterface::class)
        ));
        ServiceRegistry::getInstance()->register(DashboardController::class, new DashboardController(
            ServiceRegistry::getInstance()->get(ProductServiceInterface::class),
            ServiceRegistry::getInstance()->get(CategoryServiceInterface::class),
            ServiceRegistry::getInstance()->get(StatisticsServiceInterface::class)
        ));
        ServiceRegistry::register(CategoryController::class, new CategoryController(
            ServiceRegistry::get(CategoryServiceInterface::class)
        ));
        ServiceRegistry::register(ProductController::class, new ProductController(
            ServiceRegistry::get(ProductServiceInterface::class)
        ));
    }
}