<?php

namespace SallePW\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use SallePW\Model\ProfileRepository;
use SallePW\Model\ProfileSQL;
use SallePW\Model\User;

class searchController
{
    /** @var ContainerInterface */
    private $container;

    /**
     * HelloController constructor.
     * @param ContainerInterface $container
     * @param ProfileRepository $MySQLService
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, array $args)
    {

        return $this->container->get('view')->render($response, 'search.twig', [
            'title' => 'PWPop | Search',
            'content' => 'Laura Gendrau i Pablo Gómez',
            'footer' => '',
            'searchProduct' => $_POST['nameProduct'],
            //'sizeProductesSearch'=>sizeof($this->searchProducts()),
            'productesSearch' =>$this->searchProducts(),

        ]);
    }

    public function searchProducts(){

        $productsSearch = $this->container->get('profileSQL')->getProductsSearch($_POST['nameProduct']);
        var_dump($productsSearch);
        return $productsSearch;
    }

}


