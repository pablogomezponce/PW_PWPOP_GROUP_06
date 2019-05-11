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
           'username' => 'Pepita',
           'footer' => ' ',
           'sizeProductes'=>sizeof($this->getProducts()),
           'productes' =>$this->getProducts(),
           //'preuProducte'=> $this->getProducts()[0]['price'].'€',
           //'descripcioProducte' => $this->getProducts()[0]['description']
       ];
       if (isset($_SESSION['idUser'])){
           $params['idUser']=$_SESSION['idUser'];
           $params['sessionStarted'] = $_SESSION['sessionStarted'];
           $params['profile'] = $_SESSION['profile'];
       }

       var_dump($_SESSION);
        return $this->container->get('view')->render($response, 'publicHome.twig', $params);
        //return $this->view->render($response, array('items' => $items), 'home.twig'
    }


    public function getProducts(){
        $products = $this->container->get('profileSQL')->getProducts();
        return $products;
    }







}


