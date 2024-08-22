<?php

namespace Application\Integration\Routing;

use Exception;
use Infrastructure\Request\HttpRequest;
use Infrastructure\Response\AbstractHttpResponse;
use Infrastructure\Utility\ServiceRegistry;
use Infrastructure\Utility\Singleton;

/**
 * Class Router
 *
 * This class handles the routing of HTTP requests to the appropriate controller actions.
 * It extends the Singleton class, ensuring that only one instance of the Router exists within the application.
 */
class Router extends Singleton
{
    /**
     * @var array An associative array to store registered routes.
     * The array is organized by HTTP method (e.g., GET, POST) and then by URL pattern.
     */
    protected array $routes = [];

    /**
     * Registers a route in the router.
     *
     * This method adds a new route to the router's internal storage, associating it with the specified
     * HTTP method and URL pattern.
     *
     * @param Route $route The route to be added.
     *
     * @return void
     */
    public function addRoute(Route $route): void
    {
        $method = $route->getMethod();
        $url = $route->getUrl();
        $this->routes[$method][$url] = $route;
    }

    /**
     * Matches the current HTTP request to a registered route and dispatches it.
     *
     * This method checks the current HTTP method and URL against the registered routes. If a matching
     * route is found, it creates an instance of the associated controller and calls the specified action method.
     * If no matching route is found, it throws an Exception.
     *
     * @param HttpRequest $request
     * @return AbstractHttpResponse
     * @throws Exception If no matching route is found.
     */
    public function matchRoute(HttpRequest $request): AbstractHttpResponse
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $routeUrl => $route) {
                $pattern = preg_replace('/\/:([^\/]+)/', '/(?P<$1>[^/]+)', $routeUrl);
                if (preg_match('#^' . $pattern . '$#', $url, $matches)) {
                    $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                    $controllerClass = $route->getController();
                    $controller = ServiceRegistry::getInstance()->get($controllerClass);
                    $request = new HttpRequest();

                    foreach ($route->getMiddlewares() as $middleware) {
                        $middleware->handle($request);
                    }

                    return call_user_func_array([$controller, $route->getAction()], array_merge([$request], $params));

                }
            }
        }

        throw new Exception('Route not found');
    }
}