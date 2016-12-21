<?php

namespace Core\Controllers;

use \Core\Authentication\Auth;
use \Core\Models\User;
use \Core\Models\Role;
use \Core\Models\Permission;
use \Core\Containers\Environment;
use Respect\Validation\Validator as v;
use \Core\Containers\View;
use \Core\Containers\Model;

class AdminController extends Controller
{

	public function adminUsersIndex($req, $res)
    {
        $view = new View($res, $this);
        $users = User::with('role')->get();
        $model = (new Model())->append('users',$users);
        
        return $view("admin/users", $model());
    }

	public function adminRolesIndex($req, $res)
    {
        $view = new View($res, $this);
        $roles = Role::with('permissions')->get();
        $model = (new Model())->append('roles',$roles);
        
        return $view("admin/roles", $model());
    }

	public function adminRoles($req, $res)
    {
        $roles = Role::with('permissions')->get();        
        $permissions = Permission::all();
        return $res->withJson([
        	'roles' => $roles, 
        	'permissions' => $permissions,
        	'csrf' => Environment::getGlobal('csrf')
        ]);
    }

	public function editPermissions($req, $res)
    {
        $args = $req->getParams();
        $role = Role::with('permissions')->find($req->getParam('roleID'));
        $permissions = Permission::all();
        $pnames = array_map(function($p) use ($role, $args){
        	$pname = $p['name'];
        	if(!isset($role->permissions[$pname]) && isset($args[$pname])){
        		$role->permissions()->attach($p['id']);
        		return $p['name'] . ' - on';
        	} else {
        		$role->permissions()->detach($p['id']);        		
        	}

        }, $permissions->toArray());
        $roles = Role::with('permissions')->get();

        $permissions = Permission::all();
        return $res->withJson([
        	'asd'=>$pnames,
        	'role' => $role,
        	'roles' => $roles, 
        	'permissions' => $permissions,
        	'csrf' => Environment::getGlobal('csrf')
        ]);
    }

	public function newRole($req, $res)
    {
    	$args = $req->getParams();
    	
    	$role = new Role;
    	$role->name = $args['name'];
    	$role->description = $args['description'];
    	$role->save();

        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        return $res->withJson([
        	'roles' => $roles, 
        	'permissions' => $permissions,
        	'csrf' => Environment::getGlobal('csrf')
        ]);
    }

	public function deleteRole($req, $res)
    {
        $role = Role::find($req->getParam('roleID'))->delete();        
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        return $res->withJson([
        	'roles' => $roles, 
        	'permissions' => $permissions,
            'states' => Environment::getGlobal('account_states'),
        	'csrf' => Environment::getGlobal('csrf')
        ]);
    }

	public function adminUsers($req, $res)
    {
        $users = User::with('role')->get(['id', 'email', 'first_name', 'last_name', 'username', 'type', 'state']);

        return $res->withJson([
        	'users' => $users, 
            'roles' => Role::all(),
            'states' => Environment::getGlobal('account_states'),
        	'csrf' => Environment::getGlobal('csrf')
        ]);
    }


	public function editUser($req, $res)
    {
    	$role = Role::where('name', $req->getParam('role'))->first();
    	$user = User::find($req->getParam('userID'));
    	$user->type = $role->id;
    	$user->state = $req->getParam('state');
    	$user->save();
        $users = User::with('role')->get(['id', 'email', 'first_name', 'last_name', 'username', 'type', 'state']);

        return $res->withJson([
        	'users' => $users, 
            'roles' => Role::all(),
            'states' => Environment::getGlobal('account_states'),
        	'csrf' => Environment::getGlobal('csrf')
        	
        ]);
    }

	public function deleteUser($req, $res)
    {
        $user = User::find($req->getParam('userID'))->delete();
        $users = User::with('role')->get(['id', 'email', 'first_name', 'last_name', 'username', 'type', 'state']);
        return $res->withJson([
        	'users' => $users, 
            'roles' => Role::all(),
            'states' => Environment::getGlobal('account_states'),
        	'csrf' => Environment::getGlobal('csrf')
        ]);
    }
}