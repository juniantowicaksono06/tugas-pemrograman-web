<?php

require_once('./config/loader.php');
require_once('./config/database.php');
require_once('./routers/router.php');
require_once('./utils/session.php');

$router = new Router();
$router->addRoute('GET','/register','AuthController@register')
->addMiddleware('GET', '/register', 'NotAuthMiddleware');
$router->addRoute('GET','/login','AuthController@login')->addMiddleware('GET', '/login', 'NotAuthMiddleware');
$router->addRoute('GET','/logout','AuthController@logout');

// AUTH AND REGISTER ROUTE
$router->addRoute('POST','/login','AuthController@actionLogin');
$router->addRoute('POST','/register','AuthController@actionRegister');

$router->addRoute('GET','/admin','Admin/AdminController@home')->addMiddleware('GET', '/admin', 'AuthMiddleware')
->addMiddleware('GET', '/admin', 'AdminMiddleware');

$router->addRoute('GET','/users','Admin/UserController@users')->addMiddleware('GET', '/users', 'AuthMiddleware')
->addMiddleware('GET', '/users', 'AdminMiddleware');
$router->addRoute('GET','/','User/UserController@home')->addMiddleware('GET', '/', 'AuthMiddleware');

$router->addRoute('GET','/users/create','Admin/UserController@create')->addMiddleware('GET', '/users/create', 'AuthMiddleware')
->addMiddleware('GET', '/users/create', 'AdminMiddleware');


$router->addRoute('POST','/users','Admin/UserController@actionCreate')->addMiddleware('POST', '/users', 'AuthMiddleware')
->addMiddleware('POST', '/users', 'AdminMiddleware');

$router->addRoute('GET','/users/edit/:id','Admin/UserController@edit')->addMiddleware('GET', '/users/edit/:id', 'AuthMiddleware')
->addMiddleware('GET', '/users/edit/:id', 'AdminMiddleware');

$router->addRoute('POST','/users/edit/:id','Admin/UserController@actionEdit')->addMiddleware('POST', '/users/edit/:id', 'AuthMiddleware')
->addMiddleware('POST', '/users/edit/:id', 'AdminMiddleware');

$router->addRoute('DELETE','/users/:id','Admin/UserController@actionDelete')->addMiddleware('DELETE', '/users/:id', 'AuthMiddleware')
->addMiddleware('DELETE', '/users/:id', 'AdminMiddleware');


// AMBIL METHOD DAN PATH
$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['REQUEST_URI'];

$router->handle($method, $path);