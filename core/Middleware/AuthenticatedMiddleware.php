<?php

namespace Core\Middleware;

use \Core\Containers\Environment;

class AuthenticatedMiddleware extends Middleware
{
    public function __invoke($req, $res, $next)
    {
        if(!$this->container->auth->signed()){
        	$_SESSION['sign.redirect'] = $_SERVER['REQUEST_URI'];
            return $res->withRedirect('/signin');
        }

        $res = $next($req, $res);
        return $res;
    }
}