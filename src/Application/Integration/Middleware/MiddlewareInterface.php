<?php

namespace Application\Integration\Middleware;

use Infrastructure\Request\HttpRequest;

/**
 * Interface MiddlewareInterface
 *
 * This interface defines the contract for middleware in the application.
 * Middleware classes that implement this interface can be linked together
 * to process HTTP requests in a chain of responsibility.
 */
interface MiddlewareInterface
{
    /**
     * Set the next middleware in the chain.
     *
     * This method allows linking the current middleware with another middleware,
     * forming a chain of responsibility. The method returns the next middleware
     * to facilitate method chaining.
     *
     * @param MiddlewareInterface $next The next middleware to be executed.
     * @return MiddlewareInterface The next middleware to facilitate method chaining.
     */
    public function setNext(MiddlewareInterface $next): MiddlewareInterface;

    /**
     * Handle the HTTP request.
     *
     * This method processes the HTTP request. It is typically called by the
     * previous middleware in the chain, allowing each middleware to handle
     * the request and decide whether to pass it to the next middleware.
     *
     * @param HttpRequest $request The HTTP request to be processed.
     * @return void
     */
    public function handle(HttpRequest $request): void;
}
