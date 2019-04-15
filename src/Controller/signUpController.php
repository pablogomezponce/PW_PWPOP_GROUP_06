<?php

namespace SallePW\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class signUpController
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
        return $this->container->get('view')->render($response, 'SignUp.twig', [
            'title' => 'PWPop | Sign up',
            'content' => 'Laura Gendrau i Pablo Gómez',
            'footer' => '',
            'sessionStarted' => null,
        ]);
    }

    public function helloAction(Request $request, Response $response, array $args)
    {
        echo "Hola";
    }
}


