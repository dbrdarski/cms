<?php 

namespace Core\Install;

use Core\Tasks\TaskManager;
use Core\Tasks\Task;
use Core\Tasks\Table;
use Core\Models\User;
use Core\Models\Course;
use Core\Models\Role;
use Core\Models\Permission;

class CoreInstaller{

	function __construct(){
		$tm = TaskManager::getInstance();
		$tm->add(new Table('users',
			function ($table){
				$table->increments('id');
				$table->string('username')->unique();
				$table->string('email')->unique();
				$table->char('password', 60);
				$table->string('first_name');
				$table->string('last_name');
				$table->string('image');
				$table->text('description');
				$table->integer('type');
				$table->integer('state')->default(0);
				$table->timestamps();
			})
		)
		->add(new Table('roles',
			function ($table){
				$table->increments('id');
				$table->string('name')->unique();
				$table->string('description');
				$table->timestamps();
			})
		)
		->add(new Table('permissions',
			function ($table){
				$table->increments('id');
				$table->string('name')->unique();
				$table->string('description');
				$table->timestamps();
			})
		)
		->add(new Table('permission_role',
			function ($table){
				$table->increments('id');
				$table->integer('role_id')->unsigned();
				$table->integer('permission_id')->unsigned();
				// $table->boolean('access');
				// $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');
				// $table->timestamps();
			})
		)
		->add(new Task('roles_and_permissions',
			function(){

				$permission = new Permission;
				$manageUsers = $permission->add("manageUsers", "Manage Users");
				$manageCourses = $permission->add("manageCourses", "Manage Courses");
				$subscribeCourses = $permission->add("listenCourses", "Listen Courses");

				$admin = new Role;
				$admin->name = "admin";
				$admin->description = "Administrator";
				$admin->save();

				$admin->permissions()->attach(\Core\Models\Permission::all());

				$lecturer = new Role;
				$lecturer->name = "lecturer";
				$lecturer->description = "Lecturer";
				$lecturer->save();

				$lecturer->permissions()->attach(\Core\Models\Permission::where('name', 'manageCourses')->first());

				$student = new Role;
				$student->name = "student";
				$student->description = "Student";
				$student->save();
				
				$student->permissions()->attach(\Core\Models\Permission::where('name', 'listenCourses')->first());

				// $manageCourse =$permission->add("manageCourse", "Manage Course");
				// $manageCourse =$permission->add("manageCourse", "Manage Course");
			})
		);
	}
}