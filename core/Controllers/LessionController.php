<?php

namespace Core\Controllers;

use \Core\Models\Course;
use \Core\Models\Lession;
use \Core\Containers\Environment;
// use Respect\Validation\Validator as v;
use \Core\Containers\View;
use \Core\Containers\Model;

class LessionController extends Controller{

    public function lessionIndex($req, $res, $args)
    {
        $view = new View($res, $this);
        $lession = Course::with('users')        
        ->with(['lessions' => function($q) use ($args){
            $q->where('lession_slug', $args['lession']);
        }])
        ->where('slug',$args['course'])
        ->first();
        $model = new Model($lession);
        return $view("lession", $model());
    }
}