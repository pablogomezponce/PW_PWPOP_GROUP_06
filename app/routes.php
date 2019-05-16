<?php
/**
 * Created by PhpStorm.
 * User: PW6
 * Date: 2019-04-09
 */


//Structure:
/*
$app
    ->get('/hello/{name}', 'SallePW\SlimApp\Controller\HelloController:helloAction')
    ->add('SallePW\SlimApp\Controller\Middleware\TestMiddleware');
*/


$app
    ->get('/', 'SallePW\Controller\IndexController');

$app
    ->get('/home', 'SallePW\Controller\IndexController');


$app
    ->get('/signup','SallePW\Controller\signUpController');

$app->post('/signup', \SallePW\Controller\signUpController::class . ':addToDB') ;
$app->post('/login', \SallePW\Controller\logInController::class . ':login');

$app->post('/search',\SallePW\Controller\searchController::class.':searchProducts');

$app
    ->get('/login','SallePW\Controller\logInController');

$app
    ->get('/profile',\SallePW\Controller\ProfileController::class);

$app
    ->post('/heartPressed', \SallePW\Controller\heartPressed::class . ':heartPressed');

$app
    ->add(\SallePW\Controller\Middleware\RememberCookieHandler::class);
