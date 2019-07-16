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
        $message = $this->container->get('flash')->getMessages();

        $params = [
            'title' => 'PWPop | Log in',
            'content' => 'Laura Gendrau i Pablo Gómez',
            'footer' => '',
            'sessionStarted' => null,
            'action' => 'signup',
        ];

        if (!empty($message)) $params['messages'] = $message['test'][0];

        return $this->container->get('view')->render($response, 'LogIn.twig', $params);
    }

    public function login(Request $request, Response $response, array $args){

        $exists = $this->container->get('profileSQL')->login($_POST['password'], $_POST['identifier']);
        $error = "";



        if (!empty($exists))
        {
            if (empty($exists[0]['password'])) $error = "That isn't your password!";
            if ($exists[0]['isActive'] == 0) $error = "Disabled account";

        }


        if (sizeof($exists) == 0)  $error = "There is no account for this!";


        if (empty($exists[0]['password'])){
            $response = $response->withStatus(400);
            return $this->container->get('view')->render($response, 'LogIn.twig', [
                'title' => 'PWPop | Log in',
                'content' => 'Laura Gendrau i Pablo Gómez',
                'errors' => $error,
                'info' => $_POST,
                'footer' => '',
                'sessionStarted' => null,
                'profile' => $exists,
                'action' => 'signup',
            ]);
        } else {

            $_SESSION['profile'] = $exists[0];
            $_SESSION['idUser'] = $exists[0]['email'];
            $_SESSION['sessionStarted'] = $exists[0]['username'];

            $response = $response->withStatus(200);
            return $response->withAddedHeader('ok', 1);
        }

    }
}


