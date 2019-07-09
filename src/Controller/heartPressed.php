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
        //userName serà el que agafem a indexController amb SESSION


        $idLike = $this->container->get('productSQL')->isLike($_POST['idProducte'],$_POST['idUser']);

        if ($idLike[0][0] > 0){
            $idLike = true;
            //delete
            $this->container->get('productSQL')->deleteLike($_POST['idProducte'],$_POST['idUser']);
        }else{
            $idLike = false;
            //add
            $this->container->get('productSQL')->addLike($_POST['idProducte'],$_POST['idUser']);
        }
        $response->withStatus(200);
        return json_encode(array($idLike,$_POST['idProducte'],'holi'));

    }

}