<?php


namespace SallePW\Controller;


use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class FavouritesList
{


    private $container;

    /**
     * FavouritesList constructor.
     * @param $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }


    /**
     * GET /myFavourites
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, array $args)
    {
        $products = $this->container->get('productSQL')->getFavourites($_SESSION['profile']['id']);

        return $this->container->get('view')->render($response, 'productList.twig', [
            'title' => 'PWPop | Product',
            'footer' => '',
            'products' => $products,
            'username' => $_SESSION['profile']['username'],
            'idUser' => $_SESSION['profile']['id'],
            //'idUser' => 2,


            //'sessionStarted' => null,
        ]);
    }


}