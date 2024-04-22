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


$router->addRoute('GET','/','User/UserController@home')->addMiddleware('GET', '/', 'AuthMiddleware');

$router->addRoute('GET','/admin/users','Admin/UserController@users')->addMiddleware('GET', '/admin/users', 'AuthMiddleware')
->addMiddleware('GET', '/admin/users', 'AdminMiddleware');

$router->addRoute('GET','/admin/users/create','Admin/UserController@create')->addMiddleware('GET', '/admin/users/create', 'AuthMiddleware')
->addMiddleware('GET', '/admin/users/create', 'AdminMiddleware');


$router->addRoute('POST','/admin/users','Admin/UserController@actionCreate')->addMiddleware('POST', '/admin/users', 'AuthMiddleware')
->addMiddleware('POST', '/admin/users', 'AdminMiddleware');

$router->addRoute('GET','/admin/users/edit/:id','Admin/UserController@edit')->addMiddleware('GET', '/admin/users/edit/:id', 'AuthMiddleware')
->addMiddleware('GET', '/admin/users/edit/:id', 'AdminMiddleware');

$router->addRoute('PUT','/admin/users/:id','Admin/UserController@actionEdit')->addMiddleware('PUT', '/admin/users/edit/:id', 'AuthMiddleware')
->addMiddleware('PUT', '/admin/users/:id', 'AdminMiddleware');

$router->addRoute('DELETE','/admin/users/:id','Admin/UserController@actionDelete')->addMiddleware('DELETE', '/admin/users/:id', 'AuthMiddleware')
->addMiddleware('DELETE', '/admin/users/:id', 'AdminMiddleware');

$router->addRoute('GET','/admin/users/activate/:id','Admin/UserController@actionActivate')->addMiddleware('GET', '/admin/users/activate/:id', 'AuthMiddleware')
->addMiddleware('GET', '/admin/users/activate/:id', 'AdminMiddleware');


$router->addRoute('GET','/admin/publishers','Admin/PublisherController@publishers')->addMiddleware('GET', '/admin/publishers', 'AuthMiddleware')
->addMiddleware('GET', '/admin/publishers', 'AdminMiddleware');

$router->addRoute('GET','/admin/publishers/create','Admin/PublisherController@create')->addMiddleware('GET', '/admin/publishers/create', 'AuthMiddleware')
->addMiddleware('GET', '/admin/publishers/create', 'AdminMiddleware');

$router->addRoute('POST','/admin/publishers','Admin/PublisherController@actionCreate')->addMiddleware('POST', '/admin/publishers', 'AuthMiddleware')
->addMiddleware('POST', '/admin/publishers', 'AdminMiddleware');

$router->addRoute('GET','/admin/publishers/edit/:id','Admin/PublisherController@edit')->addMiddleware('GET', '/admin/publishers/edit/:id', 'AuthMiddleware')
->addMiddleware('GET', '/admin/publishers/edit/:id', 'AdminMiddleware');

$router->addRoute('PUT','/admin/publishers/:id','Admin/PublisherController@actionEdit')->addMiddleware('PUT', '/admin/publishers/edit/:id', 'AuthMiddleware')
->addMiddleware('PUT', '/admin/publishers/:id', 'AdminMiddleware');

$router->addRoute('DELETE','/admin/publishers/:id','Admin/PublisherController@actionDelete')->addMiddleware('DELETE', '/admin/publishers/:id', 'AuthMiddleware')
->addMiddleware('DELETE', '/admin/publishers/:id', 'AdminMiddleware');

$router->addRoute('GET','/admin/publishers/activate/:id','Admin/PublisherController@actionActivate')->addMiddleware('GET', '/admin/publishers/activate/:id', 'AuthMiddleware')
->addMiddleware('GET', '/admin/publishers/activate/:id', 'AdminMiddleware');




$router->addRoute('GET','/admin/authors','Admin/AuthorController@authors')->addMiddleware('GET', '/admin/authors', 'AuthMiddleware')
->addMiddleware('GET', '/admin/authors', 'AdminMiddleware');

$router->addRoute('GET','/admin/authors/create','Admin/AuthorController@create')->addMiddleware('GET', '/admin/authors/create', 'AuthMiddleware')
->addMiddleware('GET', '/admin/authors/create', 'AdminMiddleware');

$router->addRoute('POST','/admin/authors','Admin/AuthorController@actionCreate')->addMiddleware('POST', '/admin/authors', 'AuthMiddleware')
->addMiddleware('POST', '/admin/authors', 'AdminMiddleware');

$router->addRoute('GET','/admin/authors/edit/:id','Admin/AuthorController@edit')->addMiddleware('GET', '/admin/authors/edit/:id', 'AuthMiddleware')
->addMiddleware('GET', '/admin/authors/edit/:id', 'AdminMiddleware');

$router->addRoute('PUT','/admin/authors/:id','Admin/AuthorController@actionEdit')->addMiddleware('PUT', '/admin/authors/edit/:id', 'AuthMiddleware')
->addMiddleware('PUT', '/admin/authors/:id', 'AdminMiddleware');

$router->addRoute('DELETE','/admin/authors/:id','Admin/AuthorController@actionDelete')->addMiddleware('DELETE', '/admin/authors/:id', 'AuthMiddleware')
->addMiddleware('DELETE', '/admin/authors/:id', 'AdminMiddleware');

$router->addRoute('GET','/admin/authors/activate/:id','Admin/AuthorController@actionActivate')->addMiddleware('GET', '/admin/authors/activate/:id', 'AuthMiddleware')
->addMiddleware('GET', '/admin/authors/activate/:id', 'AdminMiddleware');


// AMBIL METHOD DAN PATH
$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['REQUEST_URI'];

$router->handle($method, $path);