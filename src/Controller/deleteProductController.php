<?php

namespace SallePW\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use SallePW\Model\Product;

class deleteProductController
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


    /**
     * POST /delete
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function deleteProduct(Request $request, Response $response, array $args)
    {
        $this->container->get('productSQL')->removeProduct($_POST['productID']);

        return $response->withHeader('location', '/myproducts');
    }


}