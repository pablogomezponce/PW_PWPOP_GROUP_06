<?php

$container = $app->getContainer();

$container['view'] = function ($c) {
    $view = new \Slim\Views\Twig(__DIR__ . '/../src/View/templates', [
        'cache' => false //__DIR__ . '/../var/cache' //false si no vols cache (per si vols que s'autogeneri cada cop), "Directori si t'interessa"
    ]);

    $router = $c->get('router');

    $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));

    $view->addExtension(new \Slim\Views\TwigExtension($router, $uri));

    return $view;
};

