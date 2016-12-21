<?php

namespace Core\Controllers;

use \Core\Models\User;
use \Core\Containers\Environment;
use Respect\Validation\Validator as v;
use \Core\Containers\View;
use \Core\Containers\Model;

class PermissionsController extends Controller{

    static private $v;

    public function permissionsIndex($req, $res, $args)
    {
        $view = new View($res, $this);
        $model = new Model();
        return $view("signup", $model());
    }
    
    public function signUpValidator($req, $res)
    {
        $args = $req->getParams();
        reset($args);
        $field = key($args);
        $value = $args[$field];
        $validation = isset(self::$v[$field]) ? $this->validator->validate($field, self::$v[$field], $value) : false;
        // if(isset(self::$v[$field]) && self::$v[$field]->validate($value) === true){
        //     return $res->withStatus(200);
        // }
        return $validation && $validation->success() ? $res->withStatus(200) : $res->withStatus(410)->withJson($validation->errors());
    }

    public function signUp($req, $res)
    {
        $args = $req->getParams();
        $validation = $this->validator->validateRequest($req, self::$v);

        if($validation->failed()){
            return $res->withStatus(400)->withJson($validation->errors());
        }

        $user = new User;
        $user->first_name = $args['first_name'];
        $user->last_name = $args['last_name'];
        $user->email = $args['email'];
        $user->username = $args['username'];
        $user->password = $args['password'];
        $user->save();
        
        return $res->withStatus(200)->withRedirect($this->router->pathFor('user')."/".$args['username']);
    }

    public function createUser($req, $res, $args)
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