<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Core\Middleware\GuestMiddleware;
use \Core\Middleware\AuthenticatedMiddleware;
use \Core\Middleware\PermissionMiddleware;

$app->get('/', 'HomeController:homeIndex')->setName('home');
$app->get('/unauthorised-access', function($req, $res){
    $view = new \Core\Containers\View($res, $this);
    return $view('403');
});

$app->get('/user', 'UserController:userIndex')->setName('user');
$app->get('/user/{username}', 'UserController:userIndex');

// $app->get('/asd', function($req, $res){
// 	// $admins = \Core\Models\Role::find(1);
// 	echo "<pre>";
// 	var_dump(\Core\Models\User::with(['role' => function($q){ $q->with('permissions'); }])->find(1)->toArray());
// 	die();
// });

$app->group('/admin', function(){

		$this->get('/users', 'AdminController:adminUsersIndex');
		$this->post('/users', 'AdminController:adminUsers');
		$this->post('/users/delete', 'AdminController:deleteUser');
		$this->post('/users/edit', 'AdminController:editUser');

		$this->get('/roles', 'AdminController:adminRolesIndex');
		$this->post('/roles', 'AdminController:adminRoles');
		$this->post('/roles/new', 'AdminController:newRole');
		$this->post('/roles/edit', 'AdminController:editPermissions');
		$this->post('/roles/delete', 'AdminController:deleteRole');

	})
	->add(new AuthenticatedMiddleware($container))
	->add(new PermissionMiddleware($container, 'manageUsers'))
;

$app->get('/library/{slug}', 'CourseController:courseIndex');
$app->get('/lecturers', 'LecturerController:lecturerListIndex');
$app->get('/lecturers/{id}', 'LecturerController:lecturerIndex');

$app->group('', function(){
	$this->get('/library/{course}/{lession}', 'LessionController:lessionIndex');
})
	->add(new AuthenticatedMiddleware($container))
	->add(new PermissionMiddleware($container, 'listenCourses'))
;

$app->group('', function(){
	$this->get('/signup', 'AuthController:signUpIndex');
	$this->post('/signup', 'AuthController:signUp');
	$this->get('/signup/v', 'AuthController:signUpValidator');
	$this->post('/signup/v', 'AuthController:signUpValidator');
	$this->get('/signin', 'AuthController:signInIndex');
	$this->post('/signin', 'AuthController:signIn');
})->add(new GuestMiddleware($container));

$app->group('', function(){
	$this->get('/signout', 'AuthController:getSignOut');
	$this->post('/signout', 'AuthController:signOut');
})
	->add(new AuthenticatedMiddleware($container))
;
$app->group('', function(){
	$this->post('/course/new', 'CourseController:createCourse');
	$this->get('/course/new', 'CourseController:createCourseIndex');
	$this->post('/course/{slug}', 'CourseController:editCourse');
	$this->post('/course/{slug}/publish', 'CourseController:publishCourse');
	$this->get('/course/{slug}', 'CourseController:editCourseIndex');
})
	->add(new AuthenticatedMiddleware($container))
	->add(new PermissionMiddleware($container, 'manageCourses'))
;



$app->get('/install', 'InstallerController:installAll')->setName('install');
$app->get('/install/{table}', 'InstallerController:install')->setName('install');
$app->get('/reinstall', 'InstallerController:reinstallAll')->setName('reinstall');
$app->get('/reinstall/{table}', 'InstallerController:reinstall')->setName('reinstall');
$app->get('/uninstall', 'InstallerController:uninstallAll')->setName('uninstall');
$app->get('/uninstall/{table}', 'InstallerController:uninstall')->setName('uninstall');

$app->run();