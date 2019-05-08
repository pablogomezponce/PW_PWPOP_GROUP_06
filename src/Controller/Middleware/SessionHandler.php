<?php


namespace SallePW\Controller\Middleware;


use Dflydev\FigCookies\FigRequestCookies;
use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\SetCookie;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class SessionHandler
{
    private const USER = 'PWPOP_user';

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
        $adviceCookie = FigRequestCookies::get($request, self::USER);

        $isWarned = $adviceCookie->getValue();

        if (!$isWarned) {
            $response = $this->setAdviceCookie($response);
        }

        $response->getBody()->write(var_dump($request));
        //$SESSION['username'];

        return $this->container->get('view')->render($response, 'index.twig', [
            'name' => $args['name'],
            'visits' => $_SESSION['counter'],
            'isWarned' => $isWarned,
        ]);
    }

    private function setAdviceCookie(Response $response): Response
    {
        return FigResponseCookies::set(
            $response,
            SetCookie::create(self::COOKIES_ADVICE)
                ->withHttpOnly(true)
                ->withMaxAge(3600)
                ->withValue(1)
                ->withDomain('slimapp.test')
                ->withPath('/')
        );
    }
}
