<?php

use Slim\Flash\Messages;


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

$container['databaseSettings'] = function ($c){
    return array(
        'address' =>"localhost",
        'dbname' => "PWPOP",
        'userNameDB' => "homestead",
        'passwordDB' => "secret",
    );
};

$container['flash'] = function () {
    return new Messages();
};


$container['rememberCookieHandler'] = function ($c) {
    return new \SallePW\Controller\Middleware\RememberCookieHandler($c);
};

$container['profileSQL'] = function ($c){
    return new \SallePW\Model\ProfileSQL($c['databaseSettings']);
};

$container['productSQL'] = function ($c){
    return new \SallePW\Model\ProductSQL($c['databaseSettings']);
};