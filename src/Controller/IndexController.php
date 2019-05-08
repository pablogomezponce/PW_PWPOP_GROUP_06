<?php

namespace SallePW\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class IndexController
{
    /** @var ContainerInterface */
    private $container;

    /**
     * HelloController constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, array $args)
    {


        return $this->container->get('view')->render($response, 'publicHome.twig', [
            'title' => 'PWPop',
            'username' => 'Pepita',
            'footer' => ' ',
            'sessionStarted' => null,
            'sizeProductes'=>sizeof($this->getProducts()),
            'productes' =>$this->getProducts(),
            'idUser' => 3, //$_SESSION['id']
            'isLike' => true,
        ]);
    }

    public function getProducts(){
        $products = $this->container->get('profileSQL')->getProducts();
        return $products;
    }







}


