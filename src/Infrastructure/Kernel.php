<?php

namespace Infrastructure;

use Application\Integration\Bootstrap;
use Application\Integration\Database\DatabaseConnection;
use Application\Integration\Exceptions\ExceptionHandler;
use Application\Integration\Routing\Router;
use Application\Integration\Routing\RouterRegistry;
use Exception;
use Infrastructure\Request\HttpRequest;

/**
 * Class Kernel
 *
 * This class is responsible for bootstrapping the application.
 * It initializes essential components like the database connection,
 * registers routes, and handles incoming HTTP requests.
 */
class Kernel
{
    /**
     * Initializes the application and handles the incoming HTTP request.
     *
     * This method is the entry point for the application. It sets up the environment
     * by calling necessary initialization routines, such as setting up the database
     * connection and registering routes. It then processes the incoming HTTP request
     * by matching it to the appropriate route and sending the response.
     *
     * @return void
     * @throws Exception
     */
    public static function init(): void
    {
        set_exception_handler([ExceptionHandler::class, 'handle']);

        Bootstrap::init();
        DatabaseConnection::init();
        RouterRegistry::registerRoutes();

        $request = HttpRequest::getInstance();
        $response = Router::getInstance()->matchRoute($request);
        $response->send();
    }
}