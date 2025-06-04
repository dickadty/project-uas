<?php
require_once 'Router.php';

use Routes\Router;

$router = new Router();

$router->addRoute('/users', '', 'GET', '/../app/views/users/index.php');
$router->addRoute('/users/create', '', 'GET', '/../app/views/users/create.php');
$router->addRoute('/keuangan', '', 'GET', '/../app/views/keuangan/index.php');
$router->addRoute('/qurban', '', 'GET', '/../app/views/qurban/index.php');
$router->addRoute('/warga', '', 'GET', '/../app/views/warga/index.php');
$router->addRoute('/pembagian', '', 'GET', '/../app/views/pembagian_qurban/index.php');
$router->addRoute('/', '', 'GET', '/../app/views/landing.php');
$router->addRoute('/login', '', 'GET', '/../app/views/login.php');
$router->addRoute('/register', '', 'GET', '/../app/views/register.php');

// Route untuk CRUD Users
$router->addRoute('/users/create', 'UserController@create', 'GET');  // Form untuk membuat user baru
$router->addRoute('/users/store', 'UserController@store', 'POST');  // Menyimpan data user baru
$router->addRoute('/users/show', 'UserController@show', 'GET');  // Menampilkan detail user
$router->addRoute('/users/edit', 'UserController@edit', 'GET');  // Menampilkan form edit user
$router->addRoute('/users/update', 'UserController@update', 'POST');  // Menyimpan perubahan user
$router->addRoute('/users/delete', 'UserController@delete', 'POST');  // Menghapus user

return $router;
