<?php

namespace Core\Validation;

class CustomValidator
{
	private static $methods = [
		'alpha' => "/^[a-zA-Z]*$/",
		'alphanum' => "/^[a-zA-Z0-9_]*$/",
		'num' => "/^[0-9]*$/",
		'email' => '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'
	];
	
	private static $messages = [
		'alpha' => 'Only letters are allowed',
		'num' => 'Only number are allowed',
		'alpha' =>'Only letters and numbers are allowed',
		'email' => 'Not a valid email address',
		'minlength' => 'Minimum of %d characters are allowed',
		'maxlength' => 'Maximum of %d characters are allowed'
	];
	
	public static function addMethod($name, $method){
		if (!isset(self::$methods[$name])) {
			self::$methods[$name] = $method;
			return true;
		} else{
			throw new \Exception("Method $name already exists!");
		}
		
	}

	function __construct($name, $args)
	{
		$this->patterns[$name] = $args;
	}

   	public function __call($name, $args)
    {
        $this->patterns[$name] = $args;
        return $this;
    }

   	public static function __callStatic($name, $args)
    {   
    	$v = new self($name, $args);
    	return $v;
    }

    public function validate($input)
    {
    	$this->input = $input;
    	foreach ($this->patterns as $name => $args){
			$pattern = self::$methods[$name];
			$v = is_callable($pattern) ? call_user_func_array(\Closure::bind($pattern, $this), $args) : (bool) preg_match($pattern, $input);
	    	if ($v === false) {
	    		return new CustomValidationError(vsprintf(self::$messages[$name], $args));
	    	}
    	}
    	return $this;
    }

    public function isValid()
    {
    	return true;
    }
}