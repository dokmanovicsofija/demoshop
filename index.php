<?php

use Cassandra\Exception\UnauthorizedException;
use Infrastructure\Bootstrap;
use Infrastructure\Routing\Router;
use Infrastructure\Request\HttpRequest;

require_once __DIR__ . '/vendor/autoload.php';

Bootstrap::initialize();

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