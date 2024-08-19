<?php

namespace Infrastructure\Middleware;

use Infrastructure\Request\HttpRequest;

interface MiddlewareInterface
{
    /**
     * Handles an incoming request and performs actions before or after passing it to the next middleware.
     *
     * This method is designed to be implemented by middleware classes that will intercept an HTTP request,
     * perform some processing (e.g., authentication, logging, etc.), and then either modify the request,
     * block it, or pass it on to the next middleware or controller action.
     *
     * @param HttpRequest $request The incoming HTTP request that the middleware will process.
     * @param callable $next The next middleware or controller action in the chain to be called after this middleware.
     */
    public function handle(HttpRequest $request, callable $next);
}
