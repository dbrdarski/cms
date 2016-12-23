<?php

namespace Core\Wrappers;

class Module{

    function __construct()
    {

    }
    private static $modules = [];

    public static function init(){
        self::$modules[static::class] = new static;
    }   
    public function getModules(){
        return self::$modules;
    }
    
    const DATA = [];
    const CONTROLLERS = [];
    const ROUTES = [];
    const MIDDLEWARES = [];

}