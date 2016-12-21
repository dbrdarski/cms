<?php

namespace Core\Middleware;

use \Core\Containers\Environment;

class GuestMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        if($this->container->auth->signed()){
            return $response->withRedirect('/');
        }

        $response = $next($request, $response);
        return $response;
    }
}