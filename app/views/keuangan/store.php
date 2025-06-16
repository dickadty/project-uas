<?php

use App\Controllers\KeuanganController;

require_once 'config/db.php';
require_once 'app/controllers/KeuanganController.php';

// Inisialisasi controller


$controllerKeuangan = new KeuanganController();

$controllerKeuangan->store();
ob_start();
?>
<?php
$content = ob_get_clean();
include_once __DIR__ . '/../templates/layout.php';
?>
