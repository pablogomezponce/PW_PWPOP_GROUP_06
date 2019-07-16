<?php


namespace SallePW\Controller;


use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class deleteAccountController
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
     * POST /deleteAccount
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return false|string
     */
    public function __invoke(Request $request, Response $response, array $args)
    {
        $idUser = $_POST['idUser'];

        $this->container->get('profileSQL')->deleteAccount($idUser);

        $this->container->get('flash')->addMessage('test', 'Account deleted');
        return $request;

    }


}