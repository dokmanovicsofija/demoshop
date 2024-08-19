<?php

namespace Infrastructure;

use Application\Business\Interfaces\RepositoryInterface\LoginRepositoryInterface;
use Application\Business\Interfaces\ServiceInterface\LoginServiceInterface;
use Application\Business\Services\LoginService;
use Application\Data\SQLRepositories\LoginRepository;
use Application\Presentation\Controller\FrontController\LoginController;
use Dotenv\Dotenv;
use Exception;
use Illuminate\Database\Capsule\Manager as Capsule;
use Infrastructure\Utility\Routing\Router;
use Infrastructure\Utility\Routing\RouterRegistry;
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
     * controllers, and routes. It should be called at the start of the application.
     *
     * @throws Exception If there is an issue during the initialization process.
     */
    public static function initialize(): void
    {
        $dotenv = Dotenv::createUnsafeImmutable(__DIR__ . '/../../');
        $dotenv->load();

        self::setupDatabase();
        self::registerRepos();
        self::registerServices();
        self::registerControllers();
        self::registerRoutes();
    }

    protected static function setupDatabase(): void
    {
        $capsule = new Capsule;

        $capsule->addConnection([
            'driver'    => getenv('DB_CONNECTION'),
            'host'      => getenv('DB_HOST'),
            'database'  => getenv('DB_DATABASE'),
            'username'  => getenv('DB_USERNAME'),
            'password'  => getenv('DB_PASSWORD'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ]);

        $capsule->setAsGlobal();

        $capsule->bootEloquent();
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
    }

    /**
     * Registers all routes with the router.
     *
     * This method is responsible for registering all application routes with the router.
     * It should be implemented to define how different URL paths are handled by the application.
     *
     * @return void
     * @throws Exception If there is an issue during route registration.
     *
     */
    protected static function registerRoutes(): void
    {
        RouterRegistry::registerRoutes();
    }
}