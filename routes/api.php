<?php
require_once 'Router.php';

use Routes\Router;

$router = new Router();

$router->addRoute('/dashboard', '', 'GET', '/../app/views/users/index.php');
$router->addRoute('/dashboard/create', '', 'GET', '/../app/views/users/create.php');
$router->addRoute('/api/dashboard/store', 'UserController@store', 'POST', '');
$router->addRoute('/api/keuangan/store', 'KeuanganController@store', 'POST', '');
$router->addRoute('/dashboard/delete', '', 'GET', '/../app/views/users/delete.php');
$router->addRoute('/keuangan', '', 'GET', '/../app/views/keuangan/index.php');
$router->addRoute('/keuangan/spend', '', 'GET', '/../app/views/keuangan/store.php');
$router->addRoute('/qurban', '', 'GET', '/../app/views/qurban/index.php');
$router->addRoute('/warga', '', 'GET', '/../app/views/warga/index.php');
$router->addRoute('/pembagian', '', 'GET', '/../app/views/pembagian_qurban/index.php');
$router->addRoute('/qr/read', '', 'GET', '/../app/views/pembagian_qurban/read_qr.php');
$router->addRoute('/qrcode', '', 'GET', '/../app/views/qrcode/index.php');
$router->addRoute('/', '', 'GET', '/../app/views/landing.php');
$router->addRoute('/login', '', 'GET', '/../app/views/login.php');
$router->addRoute('/register', '', 'GET', '/../app/views/register.php');



return $router;
