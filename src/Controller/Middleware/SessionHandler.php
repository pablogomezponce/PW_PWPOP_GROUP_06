<?php


namespace SallePW\Controller\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class SessionHandler
{
    public function __invoke(Request $request, Response $response, callable $next)
    {

        if (!isset($_SESSION['id'])){
            var_dump($_SESSION);
            //header('Location: /profile' );

        }
        return $next($request, $response);
    }
}
