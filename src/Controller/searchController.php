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


    /**
     * GET search
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, array $args)
    {
        //return $request->withHeader('Location', 'search') ->withAttribute("q", $_POST['nameProduct']);
        return $this->container->get('view')->render($response, 'search.twig', [
            'title' => 'PWPop | Search',
            'content' => 'Laura Gendrau i Pablo Gómez',
            'footer' => '',
            'searchProduct' => $_POST['nameProduct'],
            'productesSearch' => $this->searchProducts(),


        ]);
    }

    /**
     * TODO: Describe
     * @return mixed
     */
    public function searchProducts()
    {
        $productsSearch = $this->container->get('productSQL')->getProductsSearch($_POST['nameProduct']);
        return $productsSearch;
    }

}

























