<?php

namespace Core\Containers;

use \Core\Containers\Environment;

Class Model{

	private $model;

	function __invoke($path = null)
	{	
		if ($path === null){			
			return $this->model;
		}
		$keys = explode(".", $path);
		$value = $this->model;
		foreach ($keys as $k => $v) {
			$value = $value[$v];
		}
		return $value;
	}

	function __construct($model = [])
	{
		return $this->model = $this->toArray($model);
	}

	private function toArray($data)
	{
		return is_object($data) && method_exists($data, 'toArray') ? $data->toArray() : $data;
	}

	function append($name, $value)
	{
		$this->model[$name] = $this->toArray($value);
		return $this;
	}
}