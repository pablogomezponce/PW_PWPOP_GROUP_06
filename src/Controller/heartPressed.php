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
        //si es null voldra dir que la sessio no esta iniciada
        //idUser serà el que agafem a indexController amb SESSION
       // $idLike = isLike($_POST['idProducte'],3);




        return json_encode(array(true,$_POST['idProducte'],3));

    }

}