<?php
require_once 'Router.php';

use Routes\Router;

$router = new Router();

// route ke controller
$router->addRoute('/api/users', 'UserController@index', 'GET');
$router->addRoute('/api/keuangan', 'KeuanganController@index', 'GET');

// route ke view langsung
$router->addRoute('/users', '', 'GET', '/../app/views/users/index.php');
$router->addRoute('/keuangan', '', 'GET', '/../app/views/keuangan/index.php');
$router->addRoute('/qurban', '', 'GET', '/../app/views/qurban/index.php');
$router->addRoute('/warga', '', 'GET', '/../app/views/warga/index.php');
$router->addRoute('/pembagian', '', 'GET', '/../app/views/pembagian_qurban/index.php');
$router->addRoute('/', '', 'GET', '/../app/views/templates/layout.php');

return $router;