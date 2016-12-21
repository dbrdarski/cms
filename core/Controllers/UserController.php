<?php

namespace Core\Controllers;

use \Core\Models\User;
// use Respect\Validation\Validator as v;
// use \Core\Containers\Environment;
use \Core\Containers\View;
use \Core\Containers\Model;

class UserController extends Controller{

    public function userIndex($req, $res, $args)
    {
        $view = new View($res, $this);
        $user = User::where('username', $args['username'])->first();
        $model = new Model($user);
        
        return $view("user", $model());
    }

    public function editUserIndex($req, $res, $args)
    {
        $User = User::with('users')->where('slug', $args['slug'])->get()->first();
        $view = new View($res, $this);
        $model = new Model($User);
        $model->append('saveButton', 'Create');
        return $view('newUser', $model());
    }
        
    public function editUser($req, $res)
    {
        $args = $req->getParams();
        $validation = $this->validator->validate($req, [
            'name' => v::notEmpty()->alpha(),
            'slug' => v::notEmpty()->slug()
        ]);

        if($validation->failed()){
            //faliure
        }

        $c = User::findOrFail($args['id']);
        $c->title = $args['title'];
        $c->slug = $args['slug'];
        $c->description = isset($args['description']) ? $args['description'] : "";
        $c->video = isset($args['video']) ? $args['video'] : "";
        $c->color = isset($args['color']) ? $args['color'] : 0;
        $c->image = isset($args['image']) ? $args['image'] : "";
        $c->level = isset($args['level']) ? $args['level'] : 0;
        $c->save();

        return $res->withJson(['redirect' => '/User/' .  $c['slug']]);
    }
        
    public function createUser($req, $res)
    {   
        $args = $req->getParams();
        $validation = $this->validator->validate($req, [
            'name' => v::notEmpty()->alpha(),
            'slug' => v::notEmpty()->slug()
        ]);

        if($validation->failed()){
            //faliure
        }

        $c = new User;
        $c->title = $args['title'];
        $c->slug = $args['slug'];
        $c->description = isset($args['description']) ? $args['description'] : "";
        $c->video = isset($args['video']) ? $args['video'] : "";
        $c->color = isset($args['color']) ? $args['color'] : 0;
        $c->image = isset($args['image']) ? $args['image'] : "";
        $c->level = isset($args['level']) ? $args['level'] : 0;
        $c->save();

        return $res->withJson(['redirect' => '/User/' .  $c['slug']]);
    }
    // public function createLession($req, $res)
    // {   

    // }    
}