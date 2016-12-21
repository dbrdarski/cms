<?php

namespace Core\Containers;

class Environment{
	static private $options = [];
	
	static function setGlobal($name, $value)
	{		
		self::$options[$name] = $value;
	}
	static function getGlobal($name)
	{
		return isset(self::$options[$name]) ? self::$options[$name] : null;
	}
	static function getGlobals()
	{
		return self::$options;
	}
}