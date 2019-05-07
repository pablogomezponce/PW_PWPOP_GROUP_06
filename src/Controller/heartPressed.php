<?php

namespace SallePW\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class heartPressed
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
            'content' => 'Laura Gendrau i Pablo Gómez',
            'footer' => ''
        ]);


    }

    public function heartPressed(Request $request, Response $response, array $args){
        return $this->container->get('User')->getId();
    }
    //$idUser = $this->container->get('User')->getId();

    //var_dump($idUser);

    //var_dump($_POST['idProducte']);

    //return $_POST['idProducte'];
    //return $this->container->get('User')->getId();
}