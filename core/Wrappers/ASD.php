<?php

namespace Core\Wrappers;

class ASD extends Module{    
    protected $routes = [
    ];

    public function registerRoutes($app){
        $app->get('/asd', function($req, $res){
         // $admins = \Core\Models\Role::find(1);
         echo "<pre>";
         var_dump(\Core\Models\User::with(['role' => function($q){ $q->with('permissions'); }])->find(1)->toArray());
         die();
        });        
    }

}