<?php
date_default_timezone_set('Asia/Jakarta');
use Router\Router;

require_once 'vendor/autoload.php';


require_once('./Utils/function.php');

// Load ENV File
$_ENV['ENV'] = parse_env('.env');

$router = new Router();
// $router->addRoute('GET','/register','AuthController@register')
// ->addMiddleware('GET', '/register', 'NotAuthMiddleware');
$router->group('NotAuthorizeAdmin', function($router) {
    $router->addRoute('GET','/admin/auth/login','Admin/AuthController@login');
    // RESET PASSWORD
    $router->addRoute('GET','/admin/auth/reset-password','Admin/AuthController@resetPassword');
    $router->addRoute('POST','/admin/auth/reset-password','Admin/AuthController@actionResetPassword');
    
    // UPDATE RESET PASSWORD
    $router->addRoute('GET','/admin/profile/update-reset-password/:id','Admin/AuthController@updateResetPassword');
    $router->addRoute('PUT','/admin/auth/update-reset-password/:id','Admin/AuthController@actionUpdateResetPassword');
    // AUTH AND REGISTER ROUTE
    $router->addRoute('POST','/admin/auth/login','Admin/AuthController@actionLogin');
});
// $router->addRoute('GET','/admin/auth/login','Admin/AuthController@login')
// ->addMiddleware('GET', '/admin/auth/login', 'NotAuthMiddleware');

$router->group('AuthorizeAdmin', function($router) {
    $router->group('AdminMiddleware', function($router) {
        // DASHBOAR
        $router->addRoute('GET','/admin','Admin/AdminController@home');
        
        
        // EDIT PROFIL
        $router->addRoute('GET','/admin/profile/edit-profile','Admin/ProfileController@editProfile');
        $router->addRoute('POST','/admin/profile/edit-profile/:id','Admin/ProfileController@actionEdit');
        $router->addRoute('GET','/admin/auth/logout','Admin/AuthController@logout');

        // USER MANAJEMEN
        $router->addRoute('GET','/admin/users','Admin/UserController@users');
        $router->addRoute('GET','/admin/users/create','Admin/UserController@create');
        $router->addRoute('POST','/admin/users','Admin/UserController@actionCreate');
        $router->addRoute('GET','/admin/users/edit/:id','Admin/UserController@edit');
        $router->addRoute('PUT','/admin/users/:id','Admin/UserController@actionEdit');
        $router->addRoute('DELETE','/admin/users/:id','Admin/UserController@actionDeactivate');
        $router->addRoute('GET','/admin/users/reactivate/:id','Admin/UserController@actionReActivate');

        // PENERBIT
        $router->addRoute('GET','/admin/publishers','Admin/PublisherController@publishers');
        $router->addRoute('GET','/admin/publishers/create','Admin/PublisherController@create');
        $router->addRoute('POST','/admin/publishers','Admin/PublisherController@actionCreate');
        $router->addRoute('GET','/admin/publishers/edit/:id','Admin/PublisherController@edit');
        $router->addRoute('PUT','/admin/publishers/:id','Admin/PublisherController@actionEdit');
        $router->addRoute('DELETE','/admin/publishers/:id','Admin/PublisherController@actionDelete');
        $router->addRoute('GET','/admin/publishers/activate/:id','Admin/PublisherController@actionActivate');
        

        // PROVINSI
        $router->addRoute('GET','/admin/provinces','Admin/ProvinceController@province');
        $router->addRoute('GET','/admin/provinces/create','Admin/ProvinceController@create');
        $router->addRoute('POST','/admin/provinces','Admin/ProvinceController@actionCreate');
        $router->addRoute('DELETE','/admin/provinces/:id','Admin/ProvinceController@actionDeactivate');
        $router->addRoute('GET','/admin/provinces/reactivate/:id','Admin/ProvinceController@actionReactivate');
        $router->addRoute('GET','/admin/provinces/edit/:id','Admin/ProvinceController@edit');
        $router->addRoute('PUT','/admin/provinces/:id','Admin/ProvinceController@actionEdit');

        // KATEGORI
        $router->addRoute('GET','/admin/categories','Admin/CategoryController@category');
        $router->addRoute('GET','/admin/categories/create','Admin/CategoryController@create');
        $router->addRoute('POST','/admin/categories','Admin/CategoryController@actionCreate');
        $router->addRoute('DELETE','/admin/categories/:id','Admin/CategoryController@actionDeactivate');
        $router->addRoute('GET','/admin/categories/reactivate/:id','Admin/CategoryController@actionReactivate');
        $router->addRoute('GET','/admin/categories/edit/:id','Admin/CategoryController@edit');
        $router->addRoute('PUT','/admin/categories/:id','Admin/CategoryController@actionEdit');

        // KOTA
        $router->addRoute('GET','/admin/cities','Admin/CityController@city');
        $router->addRoute('GET','/admin/cities/create','Admin/CityController@create');
        $router->addRoute('POST','/admin/cities','Admin/CityController@actionCreate');
        $router->addRoute('DELETE','/admin/cities/:id','Admin/CityController@actionDeactivate');
        $router->addRoute('GET','/admin/cities/reactivate/:id','Admin/CityController@actionReactivate');
        $router->addRoute('GET','/admin/cities/edit/:id','Admin/CityController@edit');
        $router->addRoute('PUT','/admin/cities/:id','Admin/CityController@actionEdit');

        // PENGARANG
        $router->addRoute('GET','/admin/authors','Admin/AuthorController@authors');
        $router->addRoute('GET','/admin/authors/create','Admin/AuthorController@create');
        $router->addRoute('POST','/admin/authors','Admin/AuthorController@actionCreate');
        $router->addRoute('GET','/admin/authors/edit/:id','Admin/AuthorController@edit');
        $router->addRoute('PUT','/admin/authors/:id','Admin/AuthorController@actionEdit');
        $router->addRoute('DELETE','/admin/authors/:id','Admin/AuthorController@actionDelete');
        $router->addRoute('GET','/admin/authors/activate/:id','Admin/AuthorController@actionActivate');

        // BUKU
        $router->addRoute('GET','/admin/books','Admin/BookController@book');
        $router->addRoute('GET','/admin/books/create','Admin/BookController@create');
        $router->addRoute('POST','/admin/books','Admin/BookController@actionCreate');
        $router->addRoute('DELETE','/admin/books/:id','Admin/BookController@actionDeactivate');
        $router->addRoute('GET','/admin/books/reactivate/:id','Admin/BookController@actionReactivate');
        $router->addRoute('GET','/admin/books/edit/:id','Admin/BookController@edit');
        $router->addRoute('POST','/admin/books/:id','Admin/BookController@actionEdit');

        // PENGADAAN
        $router->addRoute('GET','/admin/procurements','Admin/ProcurementController@procurement');
        $router->addRoute('GET','/admin/procurements/book','Admin/ProcurementController@book');
        $router->addRoute('POST','/admin/procurements/select/:id','Admin/ProcurementController@actionSelectBook');
        $router->addRoute('DELETE','/admin/procurements/deselect/:id','Admin/ProcurementController@actionDeselectBook');
        $router->addRoute('GET','/admin/procurements/create','Admin/ProcurementController@create');
        $router->addRoute('POST','/admin/procurements','Admin/ProcurementController@actionCreate');
        $router->addRoute('DELETE','/admin/procurements/:id','Admin/ProcurementController@actionDeactivate');
        $router->addRoute('GET','/admin/procurements/reactivate/:id','Admin/ProcurementController@actionReactivate');
        $router->addRoute('GET','/admin/procurements/edit/:id','Admin/ProcurementController@edit');
        $router->addRoute('POST','/admin/procurements/:id','Admin/ProcurementController@actionEdit');

        // PEMINJAMAN
        $router->addRoute('GET','/admin/borrowing-books','Admin/BorrowingBookController@borrowing');
        $router->addRoute('GET','/admin/borrowing-books/book','Admin/BorrowingBookController@book');
        $router->addRoute('POST','/admin/borrowing-books/select/:id','Admin/BorrowingBookController@actionSelectBook');
        $router->addRoute('DELETE','/admin/borrowing-books/deselect/:id','Admin/BorrowingBookController@actionDeselectBook');
        $router->addRoute('GET','/admin/borrowing-books/create','Admin/BorrowingBookController@create');
        $router->addRoute('POST','/admin/borrowing-books','Admin/BorrowingBookController@actionCreate');
        $router->addRoute('POST','/admin/borrowing-books/:id','Admin/BorrowingBookController@actionReturn');

        // DENDA
        $router->addRoute('GET','/admin/setting-fines','Admin/SettingFinesController@fines');
        $router->addRoute('PUT','/admin/setting-fines','Admin/SettingFinesController@actionUpdate');
    });
});

$router->group('NotAuthorizeUser', function($router) {
    $router->addRoute('GET','/auth/login','User/AuthController@login');
    $router->addRoute('GET','/auth/register','User/AuthController@register');
    $router->addRoute('GET','/auth/login','User/AuthController@login');

    $router->addRoute('POST','/auth/login','User/AuthController@actionLogin');
    $router->addRoute('GET','/auth/reset-password','User/AuthController@resetPassword');
    $router->addRoute('POST','/auth/reset-password','User/AuthController@actionResetPassword');
    
    $router->addRoute('GET','/auth/update-reset-password/:id','User/AuthController@updateResetPassword');
    $router->addRoute('PUT','/auth/update-reset-password/:id','User/AuthController@actionUpdateResetPassword');
});

$router->group('AuthorizeUser', function($router) {
    $router->group('UserMiddleware', function($router) {
        $router->addRoute('GET','/','User/HomeController@home');
        $router->addRoute('GET','/profile/edit-profile','User/ProfileController@editProfile');
        $router->addRoute('GET','/profile/change-email/:id','User/ProfileController@editEmail');
        $router->addRoute('POST','/profile/edit-profile/:id','User/ProfileController@actionEdit');
        $router->addRoute('GET','/auth/logout','User/AuthController@logout');
    });
});


// AKTIVASI USER ADMIN
$router->addRoute('GET','/admin/users/activate/:id','Admin/UserController@activate');

// AKTIVASI USER BIASA
$router->addRoute('GET','/user/activate/:id','User/AuthController@activate');

// CHANGE EMAIL ADMIN
$router->addRoute('GET','/admin/profile/change-email/:id','Admin/ProfileController@actionEditEmail');


// $router->addRoute('POST','/register','Admin/AuthController@actionRegister');

// $router->addRoute('GET','/admin','Admin/AdminController@home')
// ->addMiddleware('GET', '/admin', 'AdminAuthMiddleware')
// ->addMiddleware('GET', '/admin', 'AdminMiddleware');



// AMBIL METHOD DAN PATH
$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['REQUEST_URI'];

$router->handle($method, $path);