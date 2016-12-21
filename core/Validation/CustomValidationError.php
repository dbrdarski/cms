<?php

namespace Core\Validation;

class CustomValidationError
{
	function __construct($msg)
	{
		$this->message = $msg;
		$this->error = $msg;
	}

    public function isValid()
    {
    	return false;
    }
}