<?php

namespace Core\Authentication;

use \Core\Models\User;
use \Core\Models\Role;
use \Core\Models\Permission;

Class Auth{

    public function user()
    {   
        if( ! $this->signed() ){
            return null;
        } elseif( ! isset($container['user']) ){
            $container['user'] = User::with(['role' => function($q){ $q->with('permissions'); }])->find($_SESSION['user']);
        }
        return $container['user'];
    }

    // public static function roles()
    // {   
    //     if ( Environment::getGlobal('roles') == null) {
    //         Environment::setGlobal('roles', Role::all());
    //     }
    //     return Environment::getGlobal('roles');
    // }

    public function permissions()
    {   
        $user = $this->user();
                
        if ( ! $user ){
            return null;
        } elseif ( ! isset( $container['permissions']) ) {
            $container['permissions'] = $user->role->permissions->map(function($p){ return $p->name;});            
        }
        return $container['permissions'];

    //     if ( Environment::getGlobal('permissions') == null) {
    //         Environment::getGlobal('permissions', Permission::all());
    //     }
    //     return Environment::getGlobal('permissions');
    }

    public function hasPermission($p){
        $permissions = $this->permissions();
        return $permissions !== null ? $permissions->contains($p) : null;
    }


    public function signed()
    {
        return isset($_SESSION['user']);
    }

    public function sign_out()
    {
        unset($_SESSION['user']);
    }

    public function attempt($username, $password)
    {
        $user = User::where('username', $username)->first();
        // echo "<pre>";
        // print_r($user);
        // die();

        if(!$user){
            return false;
        }
        
        if(password_verify($password, $user->password)){
            $_SESSION['user'] = $user->id;
            return $user;
        }
        
        return false;
    }
}