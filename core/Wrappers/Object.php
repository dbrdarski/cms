<?php

namespace Core\Wrappers;

class Object extends Generic{

    function __construct($obj)
    {
        $this->data = $obj;
        if(is_object($obj)){
            $this->isObject = true;
        } else {
            $this->isObject = false;
        }
    }

    private $data;
    private $isObject;


    public function _is()
    {
        $args = func_get_args();
        $check =  new Check($this->data);
        $return =  call_user_func_array(array($check, 'is'), $args);
        return $return;
    }
    public function _not()
    {
        $args = func_get_args();
        $check = new Check($this->data);
        $return = call_user_func_array(array($check, 'not'), $args);
        return $return;
    }
    public function _check()
    {
        $args = func_get_arsg();
        $check = new Check($this->data);
        $return = call_user_func_array(array($check, 'is'), $args);
        return $return;
    }
    public function of($obj)
    {
        return new Something($obj);
    }
    public function _($fn)
    {
        return new Something($fn($this->data, $this));
    }
    public function __get($prop)
    {
        if($this->isObject){
            return isset($this->data->{$prop}) ? new Something($this->data->{$prop}) : new Nothing;
        } else {
            return isset($this->data[$prop]) ? new Something($this->data[$prop]) : new Nothing;                
        }
    }
    public function __call($method, $arguments)
    {       
        $result = call_user_func_array(array($this->data, $method), $arguments);
        return $this;
    }
    public function _log()
    {
        echo is_object($this->data) ? "" : "<div>" . $this->data . "</div>";
        return $this;
    }    
}