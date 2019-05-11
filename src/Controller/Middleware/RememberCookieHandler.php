<?php


namespace SallePW\Controller\Middleware;

use Dflydev\FigCookies\FigRequestCookies;
use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\SetCookie;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class RememberCookieHandler{
    private const REMEMBERUSER = 'pwpop_rememberUser';

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

    public function __invoke(Request $request, Response $response, callable $nextMiddleware)
    {

        $adviceCookie = FigRequestCookies::get($request, self::REMEMBERUSER);

        $isWarned = $adviceCookie->getValue();

        if(isset($isWarned)){
            $_SESSION['profile'] = $this->container->get('profileSQL')->getUserDetails($isWarned)[0];
            $_SESSION['idUser'] = $_SESSION['profile']['email'];
            $_SESSION['sessionStarted'] = $_SESSION['profile']['username'];
        }

        if(isset($_POST['remember']) && isset($_SESSION['profile']['email'])){
            $response = $this->setAdviceCookie($response);
        }

        $response = $nextMiddleware($request, $response);

        var_dump($isWarned);
        return $this->container->get('view')->render($response, 'footer.twig', [
        ]);
    }

    private function setAdviceCookie(Response $response): Response
    {
        return FigResponseCookies::set(
            $response,
            SetCookie::create(self::REMEMBERUSER)
                ->withHttpOnly(true)
                ->withMaxAge(3600)
                ->withValue($_SESSION['profile']['email'])
                ->withDomain('pwpop.com')
                ->withPath('/')
        );
    }

}