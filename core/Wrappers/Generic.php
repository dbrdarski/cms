<?php
namespace Core\Wrappers;

class Generic{

    private $public = 'Dane';

    public function _()
    {
        return $this;
    }

    public function __invoke()
    {
        return $this->data;
    }

    public static function of($data)
    {
        if(is_object($data) && method_exists($data, 'isWrapper')){
            return $data;
        } else if( $data === null ) {
            return new Nothing();
        } else {
            return new Something($data);
        }
    }

    public function isWrapper()
    {
        return true;
    }

    public function wrapperType()
    {
        return get_class($this);
    }

    public function isNothing()
    {
        return false;
    }

    public function isSomething()
    {
        return false;
    }

    public function isError()
    {
        return false;
    }

    public function _log()
    {
        return $this;
    }

    public function __call($method, $args)
    {
        return $this;
    }

    public static function __callStatic($method, $args)
    {
        return $this;
    }

    public function __get($prop)
    {
        return $this;
    }

}