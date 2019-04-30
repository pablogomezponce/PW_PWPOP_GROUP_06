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
    ->get('/', 'SallePW\Controller\IndexController')
    ->add(\SallePW\Controller\Middleware\SessionHandler::class);
$app
    ->get('/signup','SallePW\Controller\signUpController');

$app->post('/signup', \SallePW\Controller\signUpController::class . ':addToDB') ;
$app->post('/login', \SallePW\Controller\logInController::class . ':login');

$app
    ->get('/login','SallePW\Controller\logInController');

$app
    ->get('/profile',\SallePW\Controller\ProfileController::class);
