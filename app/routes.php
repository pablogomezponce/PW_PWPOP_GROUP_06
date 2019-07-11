<?php

//Block 2 - home
$app->get('/home', \SallePW\Controller\IndexController::class)
    ->add(\SallePW\Controller\Middleware\RememberCookieHandler::class);
$app->get('/', function () use ($app){
    header("Location: /home");
});



//Block 3 - Search
$app->post('/search',\SallePW\Controller\searchController::class);


//Block 4 - Registration
$app->get('/signup',\SallePW\Controller\signUpController::class)
    ->add(\SallePW\Controller\Middleware\RememberCookieHandler::class);

$app->post('/signup', \SallePW\Controller\signUpController::class . ':addToDB')
    ->add(\SallePW\Controller\Middleware\RememberCookieHandler::class);

//Block 5 - Login
$app->post('/login', \SallePW\Controller\logInController::class . ':login')
    ->add(\SallePW\Controller\Middleware\RememberCookieHandler::class);
$app->get('/login',\SallePW\Controller\logInController::class)
    ->add(\SallePW\Controller\Middleware\RememberCookieHandler::class);

$app->get('/logout', \SallePW\Controller\LogOutController::class)
    ->add(\SallePW\Controller\Middleware\RememberCookieHandler::class);

//Block 6 - Profile page
$app->get('/profile',\SallePW\Controller\ProfileController::class)
    ->add(\SallePW\Controller\Middleware\RememberCookieHandler::class);
$app->post('/updateProfile', \SallePW\Controller\ProfileController::class . ':updateProfile')
    ->add(\SallePW\Controller\Middleware\RememberCookieHandler::class);

//Block 7 - Delete Account
$app->post('/deleteAccount', \SallePW\Controller\deleteAccountController::class);


//Block 8 - UploadProduct
$app->get('/upload', \SallePW\Controller\UploadProduct::class)
    ->add(\SallePW\Controller\Middleware\RememberCookieHandler::class);
$app->post('/upload', \SallePW\Controller\UploadProduct::class . ':post')
    ->add(\SallePW\Controller\Middleware\RememberCookieHandler::class);

//Block 9 - List PRoducts
$app->get('/myproducts', \SallePW\Controller\UserProductsController::class);

//Block 10.1 - Product overview for product owners //TODO: CONFLICT
//Block 10.2 - Product overview for product buyers
$app->get('/product',\SallePW\Controller\ProductController::class);
$app->post('/modifyProduct', \SallePW\Controller\ProductController::class . ':updateProduct');
$app->post('/deleteProduct', \SallePW\Controller\deleteProductController::class . ':deleteProduct');

//Block 11 - buy
$app->post('/buy', \SallePW\Controller\BuyController::class);


//Block 12 -
$app->post('/heartPressed', \SallePW\Controller\heartPressed::class . ':heartPressed');
$app->get('/myfavourites', \SallePW\Controller\FavouritesList::class);



