<?php
date_default_timezone_set('Asia/Jakarta');
require_once('./config/loader.php');
require_once('./config/database.php');
require_once('./routers/router.php');
require_once('./utils/session.php');

require_once 'vendor/autoload.php';

$router = new Router();
// $router->addRoute('GET','/register','AuthController@register')
// ->addMiddleware('GET', '/register', 'NotAuthMiddleware');
$router->addRoute('GET','/admin/auth/login','Admin/AuthController@login')
->addMiddleware('GET', '/admin/auth/login', 'NotAuthMiddleware');

// RESET PASSWORD
$router->addRoute('GET','/admin/auth/reset-password','Admin/AuthController@resetPassword')
->addMiddleware('GET', '/admin/auth/reset-password', 'NotAuthMiddleware');
$router->addRoute('POST','/admin/auth/reset-password','Admin/AuthController@actionResetPassword')
->addMiddleware('POST', '/admin/auth/reset-password', 'NotAuthMiddleware');

// UPDATE RESET PASSWORD
$router->addRoute('GET','/admin/profile/update-reset-password/:id','Admin/AuthController@updateResetPassword')
->addMiddleware('GET', '/admin/profile/update-reset-password/:id', 'NotAuthMiddleware');
$router->addRoute('PUT','/admin/auth/update-reset-password/:id','Admin/AuthController@actionUpdateResetPassword')
->addMiddleware('PUT', '/admin/auth/update-reset-password/:id', 'NotAuthMiddleware');

// PROFILE
$router->addRoute('GET','/admin/profile/edit-profile','Admin/ProfileController@editProfile')
->addMiddleware('GET', '/admin/profile/edit-profile', 'AdminAuthMiddleware')
->addMiddleware('GET', '/admin/profile/edit-profile', 'AdminMiddleware');

$router->addRoute('POST','/admin/profile/edit-profile/:id','Admin/ProfileController@actionEdit')
->addMiddleware('POST', '/admin/profile/edit-profile/:id', 'AdminAuthMiddleware')
->addMiddleware('POST', '/admin/profile/edit-profile/:id', 'AdminMiddleware');


$router->addRoute('GET','/admin/profile/change-email/:id','Admin/ProfileController@actionEditEmail')
->addMiddleware('GET', '/admin/profile/change-email/:id', 'AdminAuthMiddleware')
->addMiddleware('GET', '/admin/profile/change-email/:id', 'AdminMiddleware');

$router->addRoute('GET','/admin/auth/logout','Admin/AuthController@logout');

// AUTH AND REGISTER ROUTE
$router->addRoute('POST','/admin/auth/login','Admin/AuthController@actionLogin');
// $router->addRoute('POST','/register','Admin/AuthController@actionRegister');

$router->addRoute('GET','/admin','Admin/AdminController@home')
->addMiddleware('GET', '/admin', 'AdminAuthMiddleware')
->addMiddleware('GET', '/admin', 'AdminMiddleware');


$router->addRoute('GET','/','User/UserController@home')->addMiddleware('GET', '/', 'AdminAuthMiddleware');

$router->addRoute('GET','/admin/users','Admin/UserController@users')->addMiddleware('GET', '/admin/users', 'AdminAuthMiddleware')
->addMiddleware('GET', '/admin/users', 'AdminMiddleware');

$router->addRoute('GET','/admin/users/create','Admin/UserController@create')->addMiddleware('GET', '/admin/users/create', 'AdminAuthMiddleware')
->addMiddleware('GET', '/admin/users/create', 'AdminMiddleware');


$router->addRoute('POST','/admin/users','Admin/UserController@actionCreate')->addMiddleware('POST', '/admin/users', 'AdminAuthMiddleware')
->addMiddleware('POST', '/admin/users', 'AdminMiddleware');

$router->addRoute('GET','/admin/users/edit/:id','Admin/UserController@edit')->addMiddleware('GET', '/admin/users/edit/:id', 'AdminAuthMiddleware')
->addMiddleware('GET', '/admin/users/edit/:id', 'AdminMiddleware');

$router->addRoute('PUT','/admin/users/:id','Admin/UserController@actionEdit')->addMiddleware('PUT', '/admin/users/edit/:id', 'AdminAuthMiddleware')
->addMiddleware('PUT', '/admin/users/:id', 'AdminMiddleware');

$router->addRoute('DELETE','/admin/users/:id','Admin/UserController@actionDelete')->addMiddleware('DELETE', '/admin/users/:id', 'AdminAuthMiddleware')
->addMiddleware('DELETE', '/admin/users/:id', 'AdminMiddleware');

$router->addRoute('GET','/admin/users/activate/:id','Admin/UserController@actionActivate')->addMiddleware('GET', '/admin/users/activate/:id', 'AdminAuthMiddleware')
->addMiddleware('GET', '/admin/users/activate/:id', 'AdminMiddleware');


$router->addRoute('GET','/admin/publishers','Admin/PublisherController@publishers')->addMiddleware('GET', '/admin/publishers', 'AdminAuthMiddleware')
->addMiddleware('GET', '/admin/publishers', 'AdminMiddleware');

$router->addRoute('GET','/admin/publishers/create','Admin/PublisherController@create')->addMiddleware('GET', '/admin/publishers/create', 'AdminAuthMiddleware')
->addMiddleware('GET', '/admin/publishers/create', 'AdminMiddleware');

$router->addRoute('POST','/admin/publishers','Admin/PublisherController@actionCreate')->addMiddleware('POST', '/admin/publishers', 'AdminAuthMiddleware')
->addMiddleware('POST', '/admin/publishers', 'AdminMiddleware');

$router->addRoute('GET','/admin/publishers/edit/:id','Admin/PublisherController@edit')->addMiddleware('GET', '/admin/publishers/edit/:id', 'AdminAuthMiddleware')
->addMiddleware('GET', '/admin/publishers/edit/:id', 'AdminMiddleware');

$router->addRoute('PUT','/admin/publishers/:id','Admin/PublisherController@actionEdit')->addMiddleware('PUT', '/admin/publishers/edit/:id', 'AdminAuthMiddleware')
->addMiddleware('PUT', '/admin/publishers/:id', 'AdminMiddleware');

$router->addRoute('DELETE','/admin/publishers/:id','Admin/PublisherController@actionDelete')->addMiddleware('DELETE', '/admin/publishers/:id', 'AdminAuthMiddleware')
->addMiddleware('DELETE', '/admin/publishers/:id', 'AdminMiddleware');

$router->addRoute('GET','/admin/publishers/activate/:id','Admin/PublisherController@actionActivate')->addMiddleware('GET', '/admin/publishers/activate/:id', 'AdminAuthMiddleware')
->addMiddleware('GET', '/admin/publishers/activate/:id', 'AdminMiddleware');




$router->addRoute('GET','/admin/authors','Admin/AuthorController@authors')->addMiddleware('GET', '/admin/authors', 'AdminAuthMiddleware')
->addMiddleware('GET', '/admin/authors', 'AdminMiddleware');

$router->addRoute('GET','/admin/authors/create','Admin/AuthorController@create')->addMiddleware('GET', '/admin/authors/create', 'AdminAuthMiddleware')
->addMiddleware('GET', '/admin/authors/create', 'AdminMiddleware');

$router->addRoute('POST','/admin/authors','Admin/AuthorController@actionCreate')->addMiddleware('POST', '/admin/authors', 'AdminAuthMiddleware')
->addMiddleware('POST', '/admin/authors', 'AdminMiddleware');

$router->addRoute('GET','/admin/authors/edit/:id','Admin/AuthorController@edit')->addMiddleware('GET', '/admin/authors/edit/:id', 'AdminAuthMiddleware')
->addMiddleware('GET', '/admin/authors/edit/:id', 'AdminMiddleware');

$router->addRoute('PUT','/admin/authors/:id','Admin/AuthorController@actionEdit')->addMiddleware('PUT', '/admin/authors/edit/:id', 'AdminAuthMiddleware')
->addMiddleware('PUT', '/admin/authors/:id', 'AdminMiddleware');

$router->addRoute('DELETE','/admin/authors/:id','Admin/AuthorController@actionDelete')->addMiddleware('DELETE', '/admin/authors/:id', 'AdminAuthMiddleware')
->addMiddleware('DELETE', '/admin/authors/:id', 'AdminMiddleware');

$router->addRoute('GET','/admin/authors/activate/:id','Admin/AuthorController@actionActivate')->addMiddleware('GET', '/admin/authors/activate/:id', 'AdminAuthMiddleware')
->addMiddleware('GET', '/admin/authors/activate/:id', 'AdminMiddleware');


// AMBIL METHOD DAN PATH
$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['REQUEST_URI'];

$router->handle($method, $path);