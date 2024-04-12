<?php

require_once('./routers/router.php');
require_once('./utils/session.php');

$router = new Router();
$router->addRoute('GET','/register','AuthController@register');
$router->addRoute('GET','/login','AuthController@login');
// $router->addRoute('GET','/admin/tes/:id','AdminController@tes');

// AMBIL METHOD DAN PATH
$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['REQUEST_URI'];

$router->handle($method, $path);