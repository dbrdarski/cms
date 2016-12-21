<?php

namespace Core\Wrappers;

class Nothing extends Generic{

    public function __invoke()
    {
        return null;
    }
    public function isNothing()
    {
        return true;
    }
    public static function of()
    {
        return new Nothing();
    }
}