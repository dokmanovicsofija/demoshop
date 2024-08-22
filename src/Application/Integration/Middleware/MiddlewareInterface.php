<?php

namespace Application\Integration\Middleware;

use Infrastructure\Request\HttpRequest;

interface MiddlewareInterface
{
    public function setNext(MiddlewareInterface $next): MiddlewareInterface;

    public function handle(HttpRequest $request): void;
}
