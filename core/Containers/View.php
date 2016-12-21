<?php

namespace Core\Containers;

use \Core\Containers\Environment;

Class View{

	function __construct($res, $container)
	{
		$this->container = $container;
		$this->res = $res;
	}
	
	function __invoke($template, $model = [])
	{
		if( $model === null){			
			$this->container->view->render($this->res, '404', []);
			return $this->res->withStatus(404);
		}
		$this->container->view->render($this->res, $template, array_merge(Environment::getGlobals(), $model));
		return $this->res;
	}
}