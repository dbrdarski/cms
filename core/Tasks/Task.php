<?php 

namespace Core\Tasks;

use Illuminate\Database\Capsule\Manager as Capsule;

Class Task{

    public function __construct($name, $callback)
    {
        $this->name = $name;
        $this->callback = $callback;
    }

    public $name;
    public $task;
    
    public function up()
    {
        $callback = $this->callback;
        $callback();
        return $this;
    }

    public function down()
    {
        return $this;
    }
}