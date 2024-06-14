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

// AKTIVASI USER
$router->addRoute('GET','/admin/users/activate/:id','Admin/UserController@activate');

$router->addRoute('POST','/admin/profile/edit-profile/:id','Admin/ProfileController@actionEdit')
->addMiddleware('POST', '/admin/profile/edit-profile/:id', 'AdminAuthMiddleware')
->addMiddleware('POST', '/admin/profile/edit-profile/:id', 'AdminMiddleware');


$router->addRoute('GET','/admin/profile/change-email/:id','Admin/ProfileController@actionEditEmail');

$router->addRoute('GET','/admin/auth/logout','Admin/AuthController@logout');

// AUTH AND REGISTER ROUTE
$router->addRoute('POST','/admin/auth/login','Admin/AuthController@actionLogin');
// $router->addRoute('POST','/register','Admin/AuthController@actionRegister');

$router->addRoute('GET','/admin','Admin/AdminController@home')
->addMiddleware('GET', '/admin', 'AdminAuthMiddleware')
->addMiddleware('GET', '/admin', 'AdminMiddleware');


$router->addRoute('GET','/','User/UserController@home')->addMiddleware('GET', '/', 'AdminAuthMiddleware');

// USER
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
$router->addRoute('DELETE','/admin/users/:id','Admin/UserController@actionDeactivate')->addMiddleware('DELETE', '/admin/users/:id', 'AdminAuthMiddleware')
->addMiddleware('DELETE', '/admin/users/:id', 'AdminMiddleware');
$router->addRoute('GET','/admin/users/reactivate/:id','Admin/UserController@actionReActivate')->addMiddleware('GET', '/admin/users/reactivate/:id', 'AdminAuthMiddleware')
->addMiddleware('GET', '/admin/users/reactivate/:id', 'AdminMiddleware');

// PENERBIT
$router->addRoute('GET','/admin/publishers','Admin/PublisherController@publishers')->addMiddleware('GET', '/admin/publishers', 'AdminAuthMiddleware')
->addMiddleware('GET', '/admin/publishers', 'AdminMiddleware');
$router->addRoute('GET','/admin/publishers/create','Admin/PublisherController@create')->addMiddleware('GET', '/admin/publishers/create', 'AdminAuthMiddleware')
->addMiddleware('GET', '/admin/publishers/create', 'AdminMiddleware');
$router->addRoute('POST','/admin/publishers','Admin/PublisherController@actionCreate')->addMiddleware('POST', '/admin/publishers', 'AdminAuthMiddleware')
->addMiddleware('POST', '/admin/publishers', 'AdminMiddleware');
$router->addRoute('GET','/admin/publishers/edit/:id','Admin/PublisherController@edit')->addMiddleware('GET', '/admin/publishers/edit/:id', 'AdminAuthMiddleware')
->addMiddleware('GET', '/admin/publishers/edit/:id', 'AdminMiddleware');
$router->addRoute('PUT','/admin/publishers/:id','Admin/PublisherController@actionEdit')->addMiddleware('PUT', '/admin/publishers/:id', 'AdminAuthMiddleware')
->addMiddleware('PUT', '/admin/publishers/:id', 'AdminMiddleware');
$router->addRoute('DELETE','/admin/publishers/:id','Admin/PublisherController@actionDelete')->addMiddleware('DELETE', '/admin/publishers/:id', 'AdminAuthMiddleware')
->addMiddleware('DELETE', '/admin/publishers/:id', 'AdminMiddleware');
$router->addRoute('GET','/admin/publishers/activate/:id','Admin/PublisherController@actionActivate')->addMiddleware('GET', '/admin/publishers/activate/:id', 'AdminAuthMiddleware')
->addMiddleware('GET', '/admin/publishers/activate/:id', 'AdminMiddleware');

// PROVINSI
$router->addRoute('GET','/admin/provinces','Admin/ProvinceController@province')
->addMiddleware('GET', '/admin/provinces', 'AdminAuthMiddleware')
->addMiddleware('GET', '/admin/provinces', 'AdminMiddleware');
$router->addRoute('GET','/admin/provinces/create','Admin/ProvinceController@create')
->addMiddleware('GET', '/admin/provinces/create', 'AdminAuthMiddleware')
->addMiddleware('GET', '/admin/provinces/create', 'AdminMiddleware');
$router->addRoute('POST','/admin/provinces','Admin/ProvinceController@actionCreate')
->addMiddleware('POST', '/admin/provinces', 'AdminAuthMiddleware')
->addMiddleware('POST', '/admin/provinces', 'AdminMiddleware');
$router->addRoute('DELETE','/admin/provinces/:id','Admin/ProvinceController@actionDeactivate')
->addMiddleware('DELETE', '/admin/provinces/:id', 'AdminAuthMiddleware')
->addMiddleware('DELETE', '/admin/provinces/:id', 'AdminMiddleware');
$router->addRoute('GET','/admin/provinces/reactivate/:id','Admin/ProvinceController@actionReactivate')
->addMiddleware('GET', '/admin/provinces/reactivate/:id', 'AdminAuthMiddleware')
->addMiddleware('GET', '/admin/provinces/reactivate/:id', 'AdminMiddleware');
$router->addRoute('GET','/admin/provinces/edit/:id','Admin/ProvinceController@edit')
->addMiddleware('GET', '/admin/provinces/edit/:id', 'AdminAuthMiddleware')
->addMiddleware('GET', '/admin/provinces/edit/:id', 'AdminMiddleware');
$router->addRoute('PUT','/admin/provinces/:id','Admin/ProvinceController@actionEdit')
->addMiddleware('PUT', '/admin/provinces/:id', 'AdminAuthMiddleware')
->addMiddleware('PUT', '/admin/provinces/:id', 'AdminMiddleware');

// KATEGORI
$router->addRoute('GET','/admin/categories','Admin/CategoryController@category')
->addMiddleware('GET', '/admin/categories', 'AdminAuthMiddleware')
->addMiddleware('GET', '/admin/categories', 'AdminMiddleware');
$router->addRoute('GET','/admin/categories/create','Admin/CategoryController@create')
->addMiddleware('GET', '/admin/categories/create', 'AdminAuthMiddleware')
->addMiddleware('GET', '/admin/categories/create', 'AdminMiddleware');
$router->addRoute('POST','/admin/categories','Admin/CategoryController@actionCreate')
->addMiddleware('POST', '/admin/categories', 'AdminAuthMiddleware')
->addMiddleware('POST', '/admin/categories', 'AdminMiddleware');
$router->addRoute('DELETE','/admin/categories/:id','Admin/CategoryController@actionDeactivate')
->addMiddleware('DELETE', '/admin/categories/:id', 'AdminAuthMiddleware')
->addMiddleware('DELETE', '/admin/categories/:id', 'AdminMiddleware');
$router->addRoute('GET','/admin/categories/reactivate/:id','Admin/CategoryController@actionReactivate')
->addMiddleware('GET', '/admin/categories/reactivate/:id', 'AdminAuthMiddleware')
->addMiddleware('GET', '/admin/categories/reactivate/:id', 'AdminMiddleware');
$router->addRoute('GET','/admin/categories/edit/:id','Admin/CategoryController@edit')
->addMiddleware('GET', '/admin/categories/edit/:id', 'AdminAuthMiddleware')
->addMiddleware('GET', '/admin/categories/edit/:id', 'AdminMiddleware');
$router->addRoute('PUT','/admin/categories/:id','Admin/CategoryController@actionEdit')
->addMiddleware('PUT', '/admin/categories/:id', 'AdminAuthMiddleware')
->addMiddleware('PUT', '/admin/categories/:id', 'AdminMiddleware');


// KOTA
$router->addRoute('GET','/admin/cities','Admin/CityController@city')
->addMiddleware('GET', '/admin/cities', 'AdminAuthMiddleware')
->addMiddleware('GET', '/admin/cities', 'AdminMiddleware');
$router->addRoute('GET','/admin/cities/create','Admin/CityController@create')
->addMiddleware('GET', '/admin/cities/create', 'AdminAuthMiddleware')
->addMiddleware('GET', '/admin/cities/create', 'AdminMiddleware');
$router->addRoute('POST','/admin/cities','Admin/CityController@actionCreate')
->addMiddleware('POST', '/admin/cities', 'AdminAuthMiddleware')
->addMiddleware('POST', '/admin/cities', 'AdminMiddleware');
$router->addRoute('DELETE','/admin/cities/:id','Admin/CityController@actionDeactivate')
->addMiddleware('DELETE', '/admin/cities/:id', 'AdminAuthMiddleware')
->addMiddleware('DELETE', '/admin/cities/:id', 'AdminMiddleware');
$router->addRoute('GET','/admin/cities/reactivate/:id','Admin/CityController@actionReactivate')
->addMiddleware('GET', '/admin/cities/reactivate/:id', 'AdminAuthMiddleware')
->addMiddleware('GET', '/admin/cities/reactivate/:id', 'AdminMiddleware');
$router->addRoute('GET','/admin/cities/edit/:id','Admin/CityController@edit')
->addMiddleware('GET', '/admin/cities/edit/:id', 'AdminAuthMiddleware')
->addMiddleware('GET', '/admin/cities/edit/:id', 'AdminMiddleware');
$router->addRoute('PUT','/admin/cities/:id','Admin/CityController@actionEdit')
->addMiddleware('PUT', '/admin/cities/:id', 'AdminAuthMiddleware')
->addMiddleware('PUT', '/admin/cities/:id', 'AdminMiddleware');

// PENGARANG
$router->addRoute('GET','/admin/authors','Admin/AuthorController@authors')->addMiddleware('GET', '/admin/authors', 'AdminAuthMiddleware')
->addMiddleware('GET', '/admin/authors', 'AdminMiddleware');
$router->addRoute('GET','/admin/authors/create','Admin/AuthorController@create')->addMiddleware('GET', '/admin/authors/create', 'AdminAuthMiddleware')
->addMiddleware('GET', '/admin/authors/create', 'AdminMiddleware');
$router->addRoute('POST','/admin/authors','Admin/AuthorController@actionCreate')->addMiddleware('POST', '/admin/authors', 'AdminAuthMiddleware')
->addMiddleware('POST', '/admin/authors', 'AdminMiddleware');
$router->addRoute('GET','/admin/authors/edit/:id','Admin/AuthorController@edit')->addMiddleware('GET', '/admin/authors/edit/:id', 'AdminAuthMiddleware')
->addMiddleware('GET', '/admin/authors/edit/:id', 'AdminMiddleware');
$router->addRoute('PUT','/admin/authors/:id','Admin/AuthorController@actionEdit')->addMiddleware('PUT', '/admin/authors/:id', 'AdminAuthMiddleware')
->addMiddleware('PUT', '/admin/authors/:id', 'AdminMiddleware');
$router->addRoute('DELETE','/admin/authors/:id','Admin/AuthorController@actionDelete')->addMiddleware('DELETE', '/admin/authors/:id', 'AdminAuthMiddleware')
->addMiddleware('DELETE', '/admin/authors/:id', 'AdminMiddleware');
$router->addRoute('GET','/admin/authors/activate/:id','Admin/AuthorController@actionActivate')->addMiddleware('GET', '/admin/authors/activate/:id', 'AdminAuthMiddleware')
->addMiddleware('GET', '/admin/authors/activate/:id', 'AdminMiddleware');

// BUKU
$router->addRoute('GET','/admin/books','Admin/BookController@book')
->addMiddleware('GET', '/admin/books', 'AdminAuthMiddleware')
->addMiddleware('GET', '/admin/books', 'AdminMiddleware');
$router->addRoute('GET','/admin/books/create','Admin/BookController@create')
->addMiddleware('GET', '/admin/books/create', 'AdminAuthMiddleware')
->addMiddleware('GET', '/admin/books/create', 'AdminMiddleware');
$router->addRoute('POST','/admin/books','Admin/BookController@actionCreate')
->addMiddleware('POST', '/admin/books', 'AdminAuthMiddleware')
->addMiddleware('POST', '/admin/books', 'AdminMiddleware');
$router->addRoute('DELETE','/admin/books/:id','Admin/BookController@actionDeactivate')
->addMiddleware('DELETE', '/admin/books/:id', 'AdminAuthMiddleware')
->addMiddleware('DELETE', '/admin/books/:id', 'AdminMiddleware');
$router->addRoute('GET','/admin/books/reactivate/:id','Admin/BookController@actionReactivate')
->addMiddleware('GET', '/admin/books/reactivate/:id', 'AdminAuthMiddleware')
->addMiddleware('GET', '/admin/books/reactivate/:id', 'AdminMiddleware');
$router->addRoute('GET','/admin/books/edit/:id','Admin/BookController@edit')
->addMiddleware('GET', '/admin/books/edit/:id', 'AdminAuthMiddleware')
->addMiddleware('GET', '/admin/books/edit/:id', 'AdminMiddleware');
$router->addRoute('POST','/admin/books/:id','Admin/BookController@actionEdit')
->addMiddleware('POST', '/admin/books/:id', 'AdminAuthMiddleware')
->addMiddleware('POST', '/admin/books/:id', 'AdminMiddleware');


// AMBIL METHOD DAN PATH
$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['REQUEST_URI'];

$router->handle($method, $path);