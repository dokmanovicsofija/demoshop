<?php

namespace Infrastructure\Routing;

use Middleware\MiddlewareInterface;

/**
 * Class Route
 *
 * This class represents a single route within the application's routing system.
 * A route maps an HTTP method and URL to a specific controller and action.
 *
 * This class is marked as `readonly`, meaning that its properties cannot be modified after instantiation.
 */
class Route
{
    /**
     * Route constructor.
     *
     * Initializes a new route with the specified HTTP method, URL, controller, and action.
     *
     * @param string $method The HTTP method for the route (e.g., GET, POST).
     * @param string $url The URL pattern for the route.
     * @param string $controller The fully qualified class name of the controller that handles this route.
     * @param string $action The method in the controller that should be called for this route.
     * @param array $middlewares The middlewares associated with this route.
 */
    public function __construct(
        private string $method,
        private string $url,
        private string $controller,
        private string $action,
        private array $middlewares = []
    )
    {
    }

    /**
     * Get the HTTP method for the route.
     *
     * @return string The HTTP method (e.g., GET, POST).
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Get the URL pattern for the route.
     *
     * @return string The URL pattern.
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Get the controller class name for the route.
     *
     * @return string The fully qualified class name of the controller.
     */
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * Get the action (method) name for the route.
     *
     * @return string The name of the method in the controller that handles this route.
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * Get the middlewares for the route.
     *
     * @return array The middlewares associated with this route.
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    /**
     * Add a middleware to the route.
     *
     * @param MiddlewareInterface $middleware
     * @return $this
     */
    public function addMiddleware(MiddlewareInterface $middleware): self
    {
        $this->middlewares[] = $middleware;
        return $this;
    }
}
