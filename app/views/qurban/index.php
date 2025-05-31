<?php
require_once 'config/db.php';
require_once 'app/controllers/QurbanController.php';

use App\Controllers\QurbanController;

$qurban = 'qurban'; // Ganti dengan nama user yang sesuai
$controller = new QurbanController();
$qurban = $controller->index(); // Ambil data dari controller

ob_start();
?>

<div class="container mt-5">
    <a href="tambah_qurban.php" class="btn btn-primary mb-3">+ Tambah Qurban</a>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Jenis Hewan</th>
                    <th>Jumlah Ekor</th>
                    <th>Harga Total</th>
                    <th>Tahun</th>
                    <th>Peserta</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($qurban['qurban'])): ?>
                    <?php foreach ($qurban['qurban'] as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']) ?></td>
                            <td><?= htmlspecialchars(ucwords($row['jenis_hewan'])) ?></td>
                            <td><?= htmlspecialchars($row['jumlah_ekor']) ?></td>
                            <td><?= htmlspecialchars(number_format($row['harga_total'], 0, ',', '.')) ?></td>
                            <td><?= htmlspecialchars($row['tahun']) ?></td>
                            <td><?= htmlspecialchars($row['peserta_id']) ?></td>
                            <td>
                                <a href="edit_qurban.php?id=<?= $row['id'] ?>"><i class="fa fa-edit text-warning me-2"></i></a>
                                <a href="hapus_qurban.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus data qurban ini?');"><i class="fa fa-trash text-danger"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data qurban</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$content = ob_get_clean();
include_once __DIR__ . '/../templates/layout.php';
?>