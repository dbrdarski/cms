<?php

namespace Core\Wrappers;

class Faliure extends Generic{

    function __construct($msg)
    {
        $this->errorMsg = $msg;
    }

    private $errorMsg;

    public function isFaliure()
    {
        return true;
    }

    public static function of($msg)
    {
        return new Faliure($msg);
    }

    public function _log()
    {
        echo "<div>" . $this->errorMsg . "</div>";
        return $this;
    }
}