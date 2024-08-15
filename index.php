<?php

use Infrastructure\Bootstrap;
use Infrastructure\Routing\Router;
use Infrastructure\ServiceRegistry;

require_once __DIR__ . '/vendor/autoload.php';

Bootstrap::initialize();

try {
    $router = ServiceRegistry::get(Router::class);
    $router->matchRoute();
} catch (Exception $e) {
    http_response_code(404);
    echo $e->getMessage();
}