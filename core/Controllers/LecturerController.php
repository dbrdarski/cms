<?php

namespace Core\Controllers;

use \Core\Models\Course;
use \Core\Models\User;
use \Core\Containers\Environment;
// use Respect\Validation\Validator as v;
use \Core\Containers\View;
use \Core\Containers\Model;

class LecturerController extends Controller{

    public function lecturerIndex($req, $res, $args)
    {
        $view = new View($res, $this);
        $lecturer = User::where('type', 2)
        ->where('id', $args['id'])
        ->with(['courses'=> function($q){
            $q->with('users');
        }])
        
        ->first();
        $model = new Model($lecturer);
        return $view("lecturer", $model());
    }
    public function lecturerListIndex($req, $res, $args)
    {
        $view = new View($res, $this);
        $lecturers = User::where('type', 2)->get()->all();
        // echo '<pre>';
        // var_dump($lecturer);
        // die();
        $model = new Model();
        $model->append('lecturers', $lecturers);
        return $view("lecturer", $model());
    }    
}