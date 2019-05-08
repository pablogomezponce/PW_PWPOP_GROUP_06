<?php

namespace SallePW\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use SallePW\Model\ProfileRepository;
use SallePW\Model\ProfileSQL;
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
        $exists  = $this->container->get('profileSQL')->getUserDetails($_SESSION['profile']['email'])[0];

        var_dump($exists);
        return $this->container->get('view')->render($response, 'profile.twig', [
            'title' => 'PWPop | USER',
            'content' => 'Laura Gendrau i Pablo Gómez',
            'footer' => '',
            'sessionStarted' => $exists['username'],
            'username' => $exists['username'],
            'email' => $exists['email'],
            'name' => $exists['name'],
            'phone'=>$exists['phone'],
            'birthday' => $exists['birthdate'],
            'idUser' => $exists['email'],
            'sessionStarted' => $exists['username'],
        ]);
    }

}


