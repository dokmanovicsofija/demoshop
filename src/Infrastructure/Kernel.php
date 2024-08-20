<?php

namespace Infrastructure;

use Cassandra\Exception\UnauthorizedException;
use Exception;
use Infrastructure\Request\HttpRequest;
use Infrastructure\Utility\Routing\Router;
use Infrastructure\Utility\Routing\RouterRegistry;
use Infrastructure\DatabaseConnection;

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
     */
    public static function init(): void
    {
        try {
            Bootstrap::initialize();

            DatabaseConnection::init();

            RouterRegistry::registerRoutes();

            try {
                $request = new HttpRequest();
                $response = Router::getInstance()->matchRoute($request);
                $response->send();
            } catch (UnauthorizedException $e) {
                http_response_code(403);
                echo 'Access denied: ' . $e->getMessage();
            } catch (Exception $e) {
                http_response_code(404);
                echo $e->getMessage();
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo $e->getMessage();
        }
    }
}