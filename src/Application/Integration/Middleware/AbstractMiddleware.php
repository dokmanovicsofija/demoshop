<?php

namespace Application\Integration\Middleware;

use Infrastructure\Request\HttpRequest;

/**
 * Class AbstractMiddleware
 *
 * This abstract class provides a base implementation for middleware in the application.
 * It follows the Chain of Responsibility design pattern, allowing middleware to be linked
 * together and processed sequentially.
 *
 * Each middleware can handle the request and then pass it to the next middleware in the chain.
 */
abstract class AbstractMiddleware implements MiddlewareInterface
{
    /**
     * @var MiddlewareInterface|null The next middleware in the chain.
     */
    protected ?MiddlewareInterface $next = null;

    /**
     * Set the next middleware in the chain.
     *
     * This method allows linking the current middleware with another middleware,
     * forming a chain of responsibility. It returns the next middleware to facilitate
     * method chaining.
     *
     * @param MiddlewareInterface $next The next middleware to be executed.
     * @return MiddlewareInterface The next middleware to facilitate method chaining.
     */
    public function setNext(MiddlewareInterface $next): MiddlewareInterface
    {
        $this->next = $next;
        return $next;
    }

    /**
     * Handle the HTTP request.
     *
     * This method processes the request. If there is another middleware in the chain,
     * it passes the request to the next middleware by calling its `handle` method.
     *
     * @param HttpRequest $request The HTTP request to be processed.
     * @return void
     */
    public function handle(HttpRequest $request): void
    {
        $this->next?->handle($request);
    }
}