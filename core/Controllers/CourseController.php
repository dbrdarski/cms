<?php

namespace Core\Controllers;

// use \Core\Authentication\Auth;
use \Core\Models\Course;
use \Core\Containers\Environment;
use Respect\Validation\Validator as v;
use \Core\Containers\View;
use \Core\Containers\Model;

class CourseController extends Controller{

    public function courseIndex($req, $res, $args)
    {
        $view = new View($res, $this);
        $course = Course::with('lessions', 'users')->where('slug', $args['slug'])->first();
        $model = new Model($course);
        $model->append('course_slug', $model('slug'));
        return $view("course", $model());
    }

    public function createCourseIndex($req, $res)
    {
        $view = new View($res, $this);
        return $view(
            "newcourse", 
            [
                'id' => null,
                'action' => 'Create',
                'saveButton' => 'Create',
                'users' => $this->container->auth->user()
            ]
        );
    }

    public function editCourseIndex($req, $res, $args)
    {
        $course = Course::with('users')->with('lessions')->where('slug', $args['slug'])->first();
        $view = new View($res, $this);
        
        if($course === null){
            return $view('404');
        }
        
        $model = new Model($course);
        $model
            ->append('action', 'Edit')
            ->append('saveButton', 'Save')
            ->append('publishButton', $model('active') ? 'Unpublish' : 'Publish')
            ->append('publishAction', $model('active') ? 0 : 1)
        ;

        return $view('newcourse', $model());
    }
        
    public function editCourse($req, $res)
    {
        $args = $req->getParams();
        $validation = $this->validator->validateRequest($req, [
            'name' => v::notEmpty()->alpha(),
            'slug' => v::notEmpty()->slug()
        ]);

        if($validation->failed()){
            //faliure
        }

        $c = Course::findOrFail($args['id']);
        $c->title = $args['title'];
        $c->slug = $args['slug'];
        $c->description = isset($args['description']) ? $args['description'] : "";
        $c->video = isset($args['video']) ? $args['video'] : "";
        $c->color = isset($args['color']) ? $args['color'] : 0;
        $c->image = isset($args['image']) ? $args['image'] : "";
        $c->level = isset($args['level']) ? $args['level'] : 0;
        $c->save();

        return $res->withJson(['redirect' => '/course/' .  $c['slug']]);
    }
        
    public function createCourse($req, $res)
    {   
        $args = $req->getParams();
        $validation = $this->validator->validateRequest($req, [
            'name' => v::notEmpty()->alpha(),
            'slug' => v::notEmpty()->slug()
        ]);
        
        if($validation->failed()){
            //faliure
        }

        $c = new Course;

        $c->lecturer_id = $_SESSION['user'];

        $c->title = $args['title'];
        $c->slug = $args['slug'];
        $c->description = isset($args['description']) ? $args['description'] : "";
        $c->video = isset($args['video']) ? $args['video'] : "";
        $c->color = isset($args['color']) ? $args['color'] : 0;
        $c->image = isset($args['image']) ? $args['image'] : "";
        $c->level = isset($args['level']) ? $args['level'] : 0;
        $c->save();

        return $res->withJson(['redirect' => '/course/' .  $c['slug']]);
    }

    public function publishCourse($req, $res)
    {   
        $c = Course::find($req->getParam('id'));
        $c->active = $req->getParam('action');
        $c->save();
        return $res->withJson(['redirect' => '/course/' .  $c['slug']]);
    }
}