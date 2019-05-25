<?php

namespace SallePW\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class ProductController
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

        return $this->container->get('view')->render($response, 'productDetails.twig', [
            'title' => 'PWPop | Product',
            'footer' => '',
            'idProducte' => $this->getProductById()[0]['id'],
            'nomProducte' =>  $this->getProductById()[0]['title'],
            'dirFoto' =>$this->getProductById()[0]['product_image_dir'],
            'price' =>$this->getProductById()[0]['price'],
            'category' =>$this->getProductById()[0]['category'],
            'descripcio' =>$this->getProductById()[0]['description'],
            'isLike' => $this->isLike(),
            'idUser' => $_SESSION['id'],
            //'idUser' => 2,


            //'sessionStarted' => null,
        ]);
    }


    public function getProductById(){
        $product = $this->container->get('profileSQL')->getProductById($_GET['idProducte']);
        var_dump($product);
        return $product;

    }

    public function isLike(){
        $idLike = $this->container->get('profileSQL')->isLike($_GET['idProducte'],3);

        if ($idLike[0][0] > 0){
            $idLike = true;
            //delete
            $this->container->get('profileSQL')->deleteLike($_GET['idProducte'],3);
        }else{
            $idLike = false;
            //add
            $this->container->get('profileSQL')->addLike($_GET['idProducte'],3);
        }

        return json_encode(array($idLike,$_GET['idProducte'],'holi'));
    }

    public function isOwner(){
        $isOwner = $this->container->get('profileSQL')->isOwner($_GET['idProducte'],$_SESSION['id']);
        var_dump($isOwner);
        return $isOwner;
    }


}