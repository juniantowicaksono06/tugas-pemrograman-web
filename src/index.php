<?php

require_once('./config/loader.php');
require_once('./config/database.php');
require_once('./routers/router.php');
require_once('./utils/session.php');
require_once('./middleware/AuthMiddleware.php');
require_once('./middleware/AdminMiddleware.php');
require_once('./middleware/NotAuthMiddleware.php');

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
$router->addRoute('GET','/','User/UserController@home')->addMiddleware('GET', '/', 'AuthMiddleware');


// AMBIL METHOD DAN PATH
$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['REQUEST_URI'];

$router->handle($method, $path);