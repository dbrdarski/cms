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
		->add(new Table('courses', 
			function ($table){
				$table->increments('id');
				$table->integer('lecturer_id')->unsigned();
				// $table->foreign('owner')->references('id')->on('users')->onDelete('cascade');;
				$table->string('title');
				$table->string('slug');
				$table->text('description');
				$table->string('video');
				$table->string('image');
				$table->integer('level');
				$table->integer('color');
				$table->boolean('active');
				$table->timestamps();
			})
		)
		->add(new Table('course_student', 
			function ($table){
				$table->increments('id');
				$table->integer('student_id')->unsigned();
				// $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');
				$table->integer('course_id')->unsigned();
				// $table->foreign('courseId')->references('id')->on('courses')->onDelete('cascade');
				$table->integer('grade');
				$table->integer('rating');
				$table->timestamps();
			})
		)
		->add(new Table('lessions', 
			function ($table){
				$table->increments('id');
				$table->integer('course_id')->unsigned();
				$table->string('title');
				$table->string('lession_slug');
				$table->string('description');
				$table->string('video');
				$table->timestamps();
			})
		)
		->add(new Table('lession_student',
			function ($table){
				$table->increments('id');
				$table->integer('student_id')->unsigned();
				// $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');
				$table->integer('course_id')->unsigned();
				// $table->foreign('courseId')->references('id')->on('courses')->onDelete('cascade');
				$table->boolean('complete');
				$table->integer('grade');
				$table->timestamps();
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
		)
		->add(new Task('add_users_and_courses',
			function(){
			    $admin = (new \Core\Models\User)->admin();
			    $lecturer = (new \Core\Models\User)->lecturer();
			    $student = (new \Core\Models\User)->student();
			    
			    $administrator = $admin->add('admin', 'admin@course.plus', 'Dane', 'Brdarski', 'qwertybanana', 'dane.jpg',
			    'All-seeing, All-knowing. The Admin.');
			    
			    $student->add('student','student@course.plus', 'Pepe', 'Biserov', 'qwertybanana');			    
			    
			    $dane = $lecturer->add('dane', 'me@course.plus', 'Dane', 'Brdarski', 'qwertybanana', 'dane.jpg',
			    'Dane is a front end developer at Tricode and the author of this awesome app. His expertese ranges from design, HTML, CSS, to JavaScript and most recently PHP.');
			    $alex = $lecturer->add('alex', 'alex@course.plus', 'Alex', 'Pffeipher', 'qwertybanana', 'jack.jpg', 
			    'Alex is the Founder & CEO of Wayward Wild, a media incubator and content studio helping young websites, podcasts, web series, and publications stay true to their DNA.');
			    $jack = $lecturer->add('jack', 'jack@course.plus', 'Jack', 'Dunham', 'qwertybanana', 'dunham.jpg', 
			    'My name is Jack Dunham and I am a full-stack Web Application Developer and Software Developer, currently living in Philadelphia, PA. I have a Bachelor of Science in Computer Science from Northeastern University, and my primary focus and inspiration for my studies is Web Development.');			    
			    $kirby = $lecturer->add('kirby', 'kirby@course.plus', 'Kirby', 'Jones', 'qwertybanana', 'kirby.jpg',
		    	'Kirby Jones is a San Francisco based fine artist whose projects have received international attention. The 1000 Journals Project, launched in 2000, has been exhibited at the San Francisco Museum of Modern Art and the Skirball Cultural Center in Los Angeles.');
			    $larry = $lecturer->add('larry', 'larry@course.plus', 'Larry', 'Smith', 'qwertybanana', 'larry.jpg',
			    'Larry Smith is a veteran software developer and designer. Once upon a time, he was the co-founder and CEO of Virb (2007-2013), a DIY website builder for creatives which was acquired by GoDaddy in late 2013. He’s on twitter at @LarryTheSmith.');

			    $course = new \Core\Models\Course;

			    $c = User::find($alex)->createCourse([
			    	'title'=>'Laravel 101',
			    	'slug' => 'laravel-101',
			    	'description' => 'Dive into the Laravel essentials with this course by one of the core contributors.',
			 		'level' => 'Begginer',
			 		'color' => 'red',
			 		'image' => 'laravel.png',
			 		'video' => 'lnf1GdNxDbc'
			    ]);

			    Course::find($c->id)->createLession([
			    	'title'=>'Introduction', 
			    	'lession_slug' => 'introduction', 'description' => 'Lorem ispum dor sit amet, essentials with this course by one of the core contributors.',
			    	'video' => 'lnf1GdNxDbc'
			    ]);

			    Course::find($c->id)->createLession([
			    	'title'=>'Composer & Laravel Installer', 
			    	'lession_slug' => 'composer-and-laravel-installer', 'description' => 'Lorem ispum dor sit amet, essentials with this course by one of the core contributors.',
			    	'video' => 'FKUAAZSJiGY'
			    ]);

			    Course::find($c->id)->createLession([
			    	'title'=>'Laravel File Structure', 
			    	'lession_slug' => 'laravel-file-structure', 'description' => 'Lorem ispum dor sit amet, essentials with this course by one of the core contributors.',
			    	'video' => 'qmkAkoT9fjc'
			    ]);

			    Course::find($c->id)->createLession([
			    	'title'=>'Routing',
			    	'lession_slug' => 'routing', 'description' => 'Lorem ispum dor sit amet, essentials with this course by one of the core contributors.',
			    	'video' => 'UyuVR1a1lRM'
			    ]);

			    Course::find($c->id)->createLession([
			    	'title'=>'Models',
			    	'lession_slug' => 'models', 'description' => 'Lorem ispum dor sit amet, essentials with this course by one of the core contributors.',
			    	'video' => 'TZNr_ZvYp-A'
			    ]);

			    Course::find($c->id)->createLession([
			    	'title'=>'Relations',
			    	'lession_slug' => 'relations', 'description' => 'Lorem ispum dor sit amet, essentials with this course by one of the core contributors.',
			    	'video' => 'fZO19FGOLUU'
			    ]);
			    Course::find($c->id)->createLession([
			    	'title'=>'Views',
			    	'lession_slug' => 'views', 'description' => 'Lorem ispum dor sit amet, essentials with this course by one of the core contributors.',
			    	'video' => '_SlJJi_cLng'
			    ]);
			    Course::find($c->id)->createLession([
			    	'title'=>'Blade Templating',
			    	'lession_slug' => 'blade-templating', 'description' => 'Lorem ispum dor sit amet, essentials with this course by one of the core contributors.',
			    	'video' => 'kTPM2ynqZoc'
			    ]);
			    Course::find($c->id)->createLession([
			    	'title'=>'Controllers',
			    	'lession_slug' => 'controllers', 'description' => 'Lorem ispum dor sit amet, essentials with this course by one of the core contributors.',
			    	'video' => 'Yuku0C89jpw'
			    ]);
			    Course::find($c->id)->createLession([
			    	'title'=>'Authentication',
			    	'lession_slug' => 'authentication', 'description' => 'Lorem ispum dor sit amet, essentials with this course by one of the core contributors.',
			    	'video' => 'bqkt6eSsRZs'
			    ]);
			    Course::find($c->id)->createLession([
			    	'title'=>'Middleware',
			    	'lession_slug' => 'middleware', 'description' => 'Lorem ispum dor sit amet, essentials with this course by one of the core contributors.',
			    	'video' => 'bWhJJJwMvco'
			    ]);
			    Course::find($c->id)->createLession([
			    	'title'=>'Migrations',
			    	'lession_slug' => 'migrations', 'description' => 'Lorem ispum dor sit amet, essentials with this course by one of the core contributors.',
			    	'video' => 'IQnSruWG5O4'
			    ]);
			    Course::find($c->id)->createLession([
			    	'title'=>'Put it all together!',
			    	'lession_slug' => 'put-it-all-together', 'description' => 'Lorem ispum dor sit amet, essentials with this course by one of the core contributors.', 'video' => 'xDiqf74nfE0'
			    ]);

			    $c->publish()->save();

			    User::find($jack)->createCourse([
			 		'title' => 'Laravel Database Essentials',
			    	'slug' => 'laravel-database-essentials',
			 		'description' => 'Learn how take advantage of Laravel\'s built in model classes, schema builder and migration manager.',
			 		'level' => 'Intermediate',
			 		'color' => 'yellow',
			 		'image' => 'database.png'
		    	])->publish()->save();
			    User::find($kirby)->createCourse([
			 		'title' => 'ZURB Foudation Fundamentals',
			    	'slug' => 'zurb-foudation-fundamentals',
			 		'description' => 'Build responsive websites with one of the most advanced front end mobile frameworks.',
			 		'level' => 'Begginer',
			 		'color' => 'cyan',
			 		'image' => 'zurb.png'
		    	])->publish()->save();
			    User::find($jack)->createCourse([
			 		'title' => 'Laravel Templates',
			    	'slug' => 'laravel-templates',
			 		'description' => 'Laravel templating done right. Authored by the godfather of Laravel\'s own Blade templating engine.',
			 		'level' => 'Intermediate',
			 		'color' => 'violet',
			 		'image' => 'laravel.png'
		    	])->publish()->save();
			    User::find($alex)->createCourse([
			 		'title' => 'Laravel 401',
			    	'slug' => 'laravel-401',
			 		'description' => 'Dive into the Laravel essentials with this course by one of the core contributors.',
			 		'level' => 'Intermediate',
			 		'color' => 'orange',
			 		'image' => 'grunt.png'
		    	])->publish()->save();
			    User::find($kirby)->createCourse([
			 		'title' => 'SaSS is awesome!',
			    	'slug' => 'sass-is-awesome',
			 		'description' => 'Build responsive websites with one of the most advanced front end mobile frameworks.',
			 		'level' => 'Intermediate',
			 		'color' => 'violet',
			 		'image' => 'sass.png'
		    	])->publish()->save();
			    User::find($larry)->createCourse([
			 		'title' => 'Angular Pet Shop',
			    	'slug' => 'angular-pet-shop',
			 		'description' => 'Build your first Angular app. Dive into the most popular application framework developed by Google.',
			 		'level' => 'Advanced',
			 		'color' => 'orange',
			 		'image' => 'angular.png'
		    	])->publish()->save();
			    User::find($larry)->createCourse([
			 		'title' => 'Advanced Angular Directives',
			    	'slug' => 'advanced-angular-directives',
			 		'description' => 'This course will teach you everything you need to know about directives in Angular.',
			 		'level' => 'Advanced',
			 		'color' => 'yellow',
			 		'image' => 'angular.png'
		    	])->publish()->save();
			})
		);
	}
}