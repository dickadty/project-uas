<?php
session_start();

if (isset($_SESSION['nama'])) {} else {header("Location: login");}

require_once 'config/db.php';
require_once 'app/controllers/KeuanganController.php';
require_once 'app/controllers/WargaController.php';
require_once 'app/controllers/QurbanController.php';
require_once 'app/controllers/HewanController.php';
require_once 'app/controllers/UserController.php';

use App\Controllers\UserController;


$controllerUser = new UserController();

$controllerUser->store();
ob_start();
?>
<?php
$content = ob_get_clean();
include_once __DIR__ . '/../templates/layout.php';
?>
