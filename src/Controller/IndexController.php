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
       $params = [
           'title' => 'PWPop',
           'productes' =>$this->getAllProducts(),
           'messages' => $this->container->get('flash')->getMessages(),

       ];
       if (isset($_SESSION['idUser'])){
           $params['idUser']=$_SESSION['profile']['id'];
           $params['username'] = $_SESSION['profile']['username'];
           $params['sessionStarted'] = $_SESSION['sessionStarted'];
           $params['profile'] = $_SESSION['profile'];
       }

        return $this->container->get('view')->render($response, 'publicHome.twig', $params);
    }



    public function getAllProducts(){
        $products = $this->container->get('productSQL')->getAllProducts();

        return $products;
    }








}


