<?php

namespace SallePW\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class logInController
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
        return $this->container->get('view')->render($response, 'LogIn.twig', [
            'title' => 'PWPop | Log in',
            'content' => 'Laura Gendrau i Pablo Gómez',
            'footer' => '',
            'sessionStarted' => null,
        ]);
    }

    public function login(Request $request, Response $response, array $args){
        $exists = $this->container->get('profileSQL')->login($_POST['password'], $_POST['identifier']);
        $error = "";


        if (empty($exists[0]['password'])) $error = "That isn't your password!";


        if (sizeof($exists) == 0)  $error = "There is no account for this!";


        if (empty($exists[0]['password'])){
            return $this->container->get('view')->render($response, 'LogIn.twig', [
                'title' => 'PWPop | Log in',
                'content' => 'Laura Gendrau i Pablo Gómez',
                'errors' => $error,
                'info' => $_POST,
                'footer' => '',
                'sessionStarted' => null,
            ]);
        } else {
            $_SESSION['id'] = 1;
            header('Location: /profile');
        }

    }
}


