<?php

namespace SallePW\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class ProfileController
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
        return $this->container->get('view')->render($response, 'profile.twig', [
            'title' => 'PWPop | USER',
            'content' => 'Laura Gendrau i Pablo Gómez',
            'footer' => '',
            'sessionStarted' => 'USER',
            'username' => 'USER',
            'email' => 'user@user.com',
            'name' => 'John',
            'lastName' => 'Doe',
            'phone'=>'644991188',
            'birthday' => '19/01/1998'
        ]);
    }

    public function helloAction(Request $request, Response $response, array $args)
    {
        echo "Hola";
    }
}


