<?php

session_start();
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/config.php';

use \Core\Wrappers\ASD as ASD;
$s = new ASD;
ASD::init();

$class = '\Core\Wrappers\ASD';
$method = 'init';
$class::$method();
var_dump($s->getModules());
die();


$app = new \Slim\App($settings);

$container = $app->getContainer();
$container['view'] = function ($c) {
    $mustache = new \Slim\Mustache\Mustache(
        '../resources/views', // Template path
        array('charset' => 'UTF-8'), array('extension' => '.html')
    );
    return $mustache;
};

// //Override the default Not Found Handler
// $container['notFoundHandler'] = function ($c) {
//     return function ($req, $res) use ($c) {
//         $view = new \Core\Containers\View($res, $c);
//         return $view('404');
//     };
// };

$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

\Core\Containers\Environment::setGlobal('siteUrl', '/');

// $install = new \Core\Install\CoreInstaller; // create the Core install tasks

$container['InstallerController'] = function ($container) {
    return new \Core\Controllers\InstallerController($container);
};

$container['auth'] = function ($container) {
    return new \Core\Authentication\Auth;
};

\Core\Containers\Environment::setGlobal(
    'auth', 
    [
        'signed' => $container->auth->signed(),
        'user' => $container->auth->user()
    ]
);

$container['validator'] = function ($container) {
    return new \Core\Validation\Validator;
};
$container['UserController'] = function ($container) {
    return new \Core\Controllers\UserController($container);
};
$container['AuthController'] = function ($container) {
    return new \Core\Controllers\AuthController($container);
};
$container['AdminController'] = function ($container) {
    return new \Core\Controllers\AdminController($container);
};
$container['HomeController'] = function ($container) {
    return new \Core\Controllers\HomeController($container);
};
$container['csrf'] = function ($container) {
    return new \Slim\Csrf\Guard;
};

$app->add(new \Core\Middleware\CsrfMiddleware($container));
$app->add($container->csrf);


// v::with('Core\\Validation\\Rules\\');

require __DIR__ . '/../core/routes.php';