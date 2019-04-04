<?php

require __DIR__ . '/vendor/autoload.php';

$dependencies = require __DIR__ . "/dependencies.php";

// Define application routes
$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) use ($dependencies) {
    $r->addRoute('GET', '/task', [
        new \SallePW\Controller\PostTaskController($dependencies['renderer'], $dependencies['service']),
        'indexAction',
    ]);

    $r->addRoute('POST', '/task', [
        new \SallePW\Controller\PostTaskController($dependencies['renderer'],$dependencies['service']),
        'addToDB',
    ]);
});

$httpMethod = $_SERVER['REQUEST_METHOD'];

$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}

$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        echo "Sorry, the url that you are looking for does not exist.";
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        echo "Sorry, you don't have the right permissions to access here.";
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        $handler($vars);
        break;
}
