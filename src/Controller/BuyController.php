<?php


namespace SallePW\Controller;


use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class BuyController
{
        private $container;

    /**
     * BuyController constructor.
     * @param $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * POST /buy
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function __invoke(Request $request, Response $response, array $args)
    {
        $productOwner = $this->container->get('profileSQL')->getOwner($_POST['productID']);

        $ownerEmail = $productOwner['email'];
        $ownerName = $productOwner['name'];

        $productInfo = $this->container->get('productSQL')->getProductByID($_POST['productID']);

        $content = "Hi there!\n there's a customer who wants to buy your " . $productInfo['title'] . " (which you can check in <a href='http://pwpop.com/product?idProducte=" . $_POST['productID'] . "'>here</a>).
                    
                    The customer information is:
                    
                    <ul>
                    <li>username: ". $_SESSION['profile']['username'] ."</li>
                    <li>phone number: "  . $_SESSION['profile']['phone'] .  "</li>
                    </ul>
                    
                    Thank you!";

        //SEND EMAIL
        $this->container->get('emailer')->sendEmail($ownerEmail, $ownerName, $content);
        //mark as sold out
        $this->container->get('productSQL')->removeProduct($_POST['productID']);

        return $response->withHeader('location', '/home');
    }


}