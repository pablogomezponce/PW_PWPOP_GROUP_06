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
    ->get('/', function () use ($app){
        header("Location: /home");
    });

$app
    ->post('/modifyProduct', \SallePW\Controller\ProductController::class . ':updateProduct');

$app
    ->get('/myproducts', \SallePW\Controller\UserProductsController::class);

$app
    ->get('/home', 'SallePW\Controller\IndexController')
    ->add(\SallePW\Controller\Middleware\RememberCookieHandler::class);

$app
    ->get('/logout', \SallePW\Controller\LogOutController::class)
    ->add(\SallePW\Controller\Middleware\RememberCookieHandler::class . ':logout');
$app
    ->get('/signup','SallePW\Controller\signUpController')
    ->add(\SallePW\Controller\Middleware\RememberCookieHandler::class);

$app->post('/signup', \SallePW\Controller\signUpController::class . ':addToDB')
    ->add(\SallePW\Controller\Middleware\RememberCookieHandler::class);

$app->post('/updateProfile', \SallePW\Controller\ProfileController::class . ':updateProfile')
->add(\SallePW\Controller\Middleware\RememberCookieHandler::class);

$app->post('/login', \SallePW\Controller\logInController::class . ':login')
    ->add(\SallePW\Controller\Middleware\RememberCookieHandler::class);

$app
    ->get('/login','SallePW\Controller\logInController')
    ->add(\SallePW\Controller\Middleware\RememberCookieHandler::class);


$app->get('/registeringUser', \SallePW\Controller\RegisterFlashController::class)
    ->add(\SallePW\Controller\Middleware\RememberCookieHandler::class);



$app->post('/search',\SallePW\Controller\searchController::class);


$app
    ->get('/product',\SallePW\Controller\ProductController::class);


$app
    ->get('/profile',\SallePW\Controller\ProfileController::class)
    ->add(\SallePW\Controller\Middleware\RememberCookieHandler::class);

$app
    ->get('/upload', \SallePW\Controller\UploadProduct::class)
    ->add(\SallePW\Controller\Middleware\RememberCookieHandler::class);

$app
    ->post('/upload', \SallePW\Controller\UploadProduct::class . ':post')
    ->add(\SallePW\Controller\Middleware\RememberCookieHandler::class);


$app
    ->post('/heartPressed', \SallePW\Controller\heartPressed::class . ':heartPressed');
$app
    ->post('/deleteAccount', \SallePW\Controller\deleteAccountController::class);

$app
    ->get('/myfavourites', \SallePW\Controller\FavouritesList::class);

$app
    ->post('/deleteProduct', \SallePW\Controller\ProductController::class . ':deleteProduct');

$app
    ->post('/buy', \SallePW\Controller\BuyController::class);