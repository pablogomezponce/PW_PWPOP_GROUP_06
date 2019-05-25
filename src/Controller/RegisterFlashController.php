<?php


namespace SallePW\Controller;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;



final class RegisterFlashController
{
    /** @var ContainerInterface */
    private $container;

    /**
     * FlashController constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response)
    {
        $this->container
            ->get('flash')
            ->addMessage('userRegistered', "You have been registered! Please, log in!");

        return $response->withRedirect('/login', 301);
    }
}