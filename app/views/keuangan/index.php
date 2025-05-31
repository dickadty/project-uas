<?php

require_once 'app/controllers/KeuanganController.php';
use App\Controllers\KeuanganController;

$keuangan = new KeuanganController();

var_dump($keuangan->index());

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

</body>

</html>