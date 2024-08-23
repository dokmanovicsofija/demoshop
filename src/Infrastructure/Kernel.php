<?php

namespace Infrastructure;

use Application\Integration\Bootstrap;
use Application\Integration\Database\DatabaseConnection;
use Application\Integration\Routing\Router;
use Application\Integration\Routing\RouterRegistry;
use Application\Integration\Utility\PathHelper;
use Exception;
use Infrastructure\Exceptions\HttpNotFoundException;
use Infrastructure\Request\HttpRequest;
use Infrastructure\Response\HtmlResponse;
use Throwable;

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
        set_exception_handler([self::class, 'handleException']);

        Bootstrap::initialize();
        DatabaseConnection::init();
        RouterRegistry::registerRoutes();

        $request = new HttpRequest();
        $response = Router::getInstance()->matchRoute($request);
        $response->send();
    }

    /**
     * Global exception handler.
     *
     * @param Throwable $exception
     * @return void
     */
    public static function handleException(Throwable $exception): void
    {
        // Generate an appropriate error response
        if ($exception instanceof HttpNotFoundException) {
            $response = HtmlResponse::fromView(
                PathHelper::view('errors/404.php'),
                [],
                404
            );
        } else {
            $response = HtmlResponse::fromView(
                PathHelper::view('errors/500.php'),
                [],
                500
            );
        }

        $response->send();
    }
}