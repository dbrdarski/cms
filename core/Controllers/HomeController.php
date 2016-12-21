<?php

namespace Core\Controllers;

// use \Slim\Mustache\Mustache as View;
use \Core\Models\Course;
use \Core\Containers\Environment;
use \Core\Containers\View;
use \Core\Containers\Model;
// use Respect\Validation\Validator as v;

class HomeController extends Controller{

    public function homeIndex($req, $res)
    {
        $courses = Course::with('users')->where('active', true)->get();
        $view = new View($res, $this);
        $model = (new Model)->append('courses', $courses);
        
        return $view('index', $model());
    }
}