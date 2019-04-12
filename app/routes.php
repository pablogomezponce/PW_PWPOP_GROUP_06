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
    ->get('/signup','SallePW\Controller\signUpController');

$app
    ->get('/login','SallePW\Controller\logInController');