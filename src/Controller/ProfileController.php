<?php

namespace SallePW\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use SallePW\Model\ProfileRepository;
use SallePW\Model\User;

class ProfileController
{
    /** @var ContainerInterface */
    private $container;
    private $profileSQL;

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
        var_dump($_SESSION);
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

}


