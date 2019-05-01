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
        //$this->getProducts();

        var_dump($this->getProducts()[0]);

        return $this->container->get('view')->render($response, 'publicHome.twig', [
            'title' => 'PWPop',
            'username' => 'Pepita',
            'footer' => ' ',
            'sessionStarted' => null,
            'nomProducte' =>$this->getProducts()[0]['title'],
            'preuProducte'=> $this->getProducts()[0]['price']+' €',
            'descripcioProducte' => $this->getProducts()[0]['description']
        ]);

    }

    public function getProducts(){
        $products = $this->container->get('profileSQL')->getProducts();

        return $products;
    }

    /*
    public function helloAction(Request $request, Response $response, array $args)
    {
        echo "Hola";
    }*/


}


