<?php


namespace SallePW\Controller;


use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use SallePW\Model\ProfileRepository;

class UserProductsController
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
        $products = $this->container->get('productSQL')->getAllProductsByEmail($_SESSION['idUser']);


        //return $request->withHeader('Location', 'search') ->withAttribute("q", $_POST['nameProduct']);
        return $this->container->get('view')->render($response, 'productList.twig', [
            'title' => 'PWPop | My Products',
            'footer' => '',
            'products' => $products,
            'idUser' => $_SESSION['profile']['id'],
            'username' => $_SESSION['profile']['username'],
            'sessionStarted' => $_SESSION['sessionStarted'],
            'profile' => $_SESSION['profile'],
            //'sizeProductesSearch'=>sizeof($this->searchProducts()),


        ]);
    }

}