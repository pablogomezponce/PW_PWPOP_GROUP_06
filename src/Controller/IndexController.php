<?php

namespace SallePW\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class IndexController
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
        return $this->container->get('view')->render($response, 'publicHome.twig', [
            'title' => 'PWPop',
            'username' => 'Pepita',
            'footer' => ' ',
            'sessionStarted' => null,
            'nomProducte' =>'#Title',
            'descripcioProducte' => 'LoremLorem ipsum dolor sit amet, consectetur adipiscing elit. Duis eleifend massa felis, eu porta massa 
            ullamcorper sit amet. Donec tempus mattis leo, non tincidunt ipsum gravida vel. Interdum et malesuada fames ac ante ipsum primis 
            in faucibus. Praesent vitae magna finibus, finibus est feugiat, ornare est. Aenean luctus enim ac orci ultricies, a viverra tortor 
            tincidunt. Praesent in maximus odio. Sed a metus eleifend, bibendum justo nec, tincidunt arcu. Duis varius in felis non feugiat.
            Donec varius pellentesque purus, quis euismod mauris blandit quis. Vivamus hendrerit nisi metus. Sed quis elementum nunc. Nullam 
            sagittis velit vel mattis rutrum. Mauris ac tincidunt mauris. Sed eleifend fermentum orci a finibus. Donec rhoncus vestibulum sem, 
            vel luctus ipsum dapibus quis. Maecenas et odio sodales, ultricies velit sed, vehicula odio.'
        ]);
    }

    public function helloAction(Request $request, Response $response, array $args)
    {
        echo "Hola";
    }
}


