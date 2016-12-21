<?php

namespace Core\Middleware;

use \Core\Containers\Environment;

class PermissionMiddleware extends Middleware
{
    public function __construct($container, $permission)
    {
    	parent::__construct($container);
    	$this->permission = $permission;
    }

    public function __invoke($req, $res, $next)
    {
        if(!$this->container->auth->hasPermission($this->permission)){
            return $res->withRedirect('/unauthorised-access');
        }
        $res = $next($req, $res);
        return $res;
    }
}