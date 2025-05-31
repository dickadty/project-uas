<?php

require_once 'app/controllers/PembagianQurbanController.php';

use App\Controllers\PembagianQurbanController;

$pembagian = new PembagianQurbanController();
?>
<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>Jumlah</th>
        <!-- sesuaikan dengan kolom tabel -->
    </tr>
    <?php foreach ($data['pembagian'] as $row): ?>
        <tr>
            <td><?= $row['id']; ?></td>
            <td><?= $row['nama']; ?></td>
            <td><?= $row['jumlah']; ?></td>
        </tr>
    <?php endforeach; ?>
</table>