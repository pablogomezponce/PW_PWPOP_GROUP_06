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

    /**
     * add cookie
     * @param Request $request
     * @param Response $response
     * @param callable $nextMiddleware
     * @return Response
     */
    public function __invoke(Request $request, Response $response, callable $nextMiddleware)
    {
        $response = $nextMiddleware($request, $response);

        $adviceCookie = FigRequestCookies::get($request, self::REMEMBERUSER);

        $isWarned = $adviceCookie->getValue();

        if(isset($isWarned)){
            $_SESSION['profile'] = $this->container->get('profileSQL')->getUserById($isWarned)[0];
            $_SESSION['idUser'] = $_SESSION['profile']['email'];
            $_SESSION['sessionStarted'] = $_SESSION['profile']['username'];
        }
        ;
        if(isset($_POST['remember']) && $response->getStatusCode() == 200 && isset($_SESSION['profile']['id'])){
            $response = $this->setAdviceCookie($response);
        }

        return $response;
    }

    /**
     * Set cookie
     * @param Response $response
     * @return Response
     */
    private function setAdviceCookie(Response $response): Response
    {
        return FigResponseCookies::set(
            $response,
            SetCookie::create(self::REMEMBERUSER)
                ->withHttpOnly(true)
                ->withMaxAge(3600)
                ->withValue($_SESSION['profile']['id'])
                ->withDomain('pwpop.com')
                ->withPath('/')
        );
    }

    /**
     * Delete information about user
     * @param Request $request
     * @param Response $response
     * @param callable $nextMiddleware
     * @return Response
     */
    public function logout(Request $request, Response $response, callable $nextMiddleware): Response
    {
        $request = $nextMiddleware($request,$response);

        FigResponseCookies::remove($response, self::REMEMBERUSER);
        session_unset();
        $response = $response->withHeader("Location", "/home");
        return $response;
    }
}