<?php

namespace Core\Wrappers;

class Check{
    function __construct($obj)
    {
        $this->data = $obj;
        $this->callbackSuccess = &$this->defaultSuccess();
        $this->callbackFailure = &$this->defaultFailure();
        return $this;
    }
    private $data;
    private $conditionCallback;
    private $condition = true;
    public $callbackSuccess;
    public $callbackFailure;

    public function onSuccess($callback)
    {
        $this->callbackSuccess = $callback;
        return $this;
    }

    public function onFailure($callback)
    {
        $this->callbackFailure = $callback;
        return $this;
    }

    function check()
    {
        $args = func_get_args();
        $method = array_shift($args);
        $this->conditionCallback = function() use (&$method, &$args){
            $result = call_user_func_array(array($this->data, $method), $args);
            return  $result;
        };
    }

    function assert($v)
    {
        $this->condition = $v;
        return $this;
    }

    function is()
    {
        $args = func_get_args();
        $method = array_shift($args);
        $this->condition = true;
        $this->conditionCallback = function() use (&$method, &$args){
            $result = call_user_func_array(array($this->data, $method), $args);
            return  $result;
        };
        return $this;
    }

    function not()
    {
        $args = func_get_args();
        $method = array_shift($args);
        $this->condition = false;
        $this->conditionCallback = function() use (&$method, &$args){
            $result = call_user_func_array(array($this->data, $method), $args);
            return $result;
        };
        return $this;
    }

    function defaultSuccess()
    {
        $fn = function(){
            return new Something($this->data);
        };
        return $fn;
    }

    function defaultFailure()
    {
        $fn = function(){
            return new Nothing();
        };
        return $fn;
    }
    
    function resolve()
    {
        $args = func_get_args();
        // $method = array_shift($args);
        $closure = $this->conditionCallback;
        $resolve = $closure() === $this->condition;
        $result = $resolve ? $this->callbackSuccess : $this->callbackFailure;
        return $result();
    }

    public function of($obj)
    {
        return new Check($obj);
    }
}