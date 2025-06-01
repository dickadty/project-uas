<?php
require_once 'config/db.php';
require_once 'app/controllers/UserController.php';

use App\Controllers\UserController;

$controller = new UserController();
$data = $controller->index(); // Ambil data user berdasarkan role

ob_start();
?>

<div class="row">
    <!-- Card Warga -->
    <div class="col-lg-4">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Warga</h5>
                <div class="ibox-tools">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
                </div>
            </div>
            <div class="ibox-content">
                <table class="table table-hover no-margins">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['warga'] as $warga): ?>
                            <tr>
                                <td><?= htmlspecialchars($warga['nama']) ?></td>
                                <td><span class="label label-primary"><?= htmlspecialchars($warga['status']) ?></span></td>
                                <td>
                                    <!-- Tombol Lihat -->
                                    <a href="view_user.php?id=<?= $warga['id_warga'] ?>" class="me-2" title="Lihat User">
                                        <i class="fa fa-eye text-info" style="font-size: 20px;"></i>
                                    </a>

                                    <!-- Tombol Edit -->
                                    <a href="edit_user.php?id=<?= $warga['id_warga'] ?>" class="me-2" title="Edit User">
                                        <i class="fa fa-edit text-warning" style="font-size: 20px;"></i>
                                    </a>

                                    <!-- Tombol Hapus -->
                                    <a href="hapus_user.php?id=<?= $warga['id_warga'] ?>" onclick="return confirm('Yakin ingin menghapus user ini?');" title="Hapus User">
                                        <i class="fa fa-trash text-danger" style="font-size: 20px;"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Card Panitia -->
    <div class="col-lg-4">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Panitia</h5>
                <div class="ibox-tools">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
                </div>
            </div>
            <div class="ibox-content">
                <table class="table table-hover no-margins">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['panitia'] as $panitia): ?>
                            <tr>
                                <td><?= htmlspecialchars($panitia['nama']) ?></td>
                                <td><span class="label label-warning"><?= htmlspecialchars($panitia['status']) ?></span></td>
                                <td>
                                    <!-- Tombol Lihat -->
                                    <a href="view_user.php?id=<?= $panitia['id_warga'] ?>" class="me-2" title="Lihat User">
                                        <i class="fa fa-eye text-info" style="font-size: 20px;"></i>
                                    </a>

                                    <!-- Tombol Edit -->
                                    <a href="edit_user.php?id=<?= $panitia['id_warga'] ?>" class="me-2" title="Edit User">
                                        <i class="fa fa-edit text-warning" style="font-size: 20px;"></i>
                                    </a>

                                    <!-- Tombol Hapus -->
                                    <a href="hapus_user.php?id=<?= $panitia['id_warga'] ?>" onclick="return confirm('Yakin ingin menghapus user ini?');" title="Hapus User">
                                        <i class="fa fa-trash text-danger" style="font-size: 20px;"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Card Mudhohi -->
    <div class="col-lg-4">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Mudhohi</h5>
                <div class="ibox-tools">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
                </div>
            </div>
            <div class="ibox-content">
                <table class="table table-hover no-margins">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['berqurban'] as $berqurban): ?>
                            <tr>
                                <td><?= htmlspecialchars($berqurban['nama']) ?></td>
                                <td><span class="label label-success"><?= htmlspecialchars($berqurban['status']) ?></span></td>
                                <td>
                                    <!-- Tombol Lihat -->
                                    <a href="view_user.php?id=<?= $berqurban['id'] ?>" class="me-2" title="Lihat User">
                                        <i class="fa fa-eye text-info" style="font-size: 20px;"></i>
                                    </a>

                                    <!-- Tombol Edit -->
                                    <a href="edit_user.php?id=<?= $berqurban['id'] ?>" class="me-2" title="Edit User">
                                        <i class="fa fa-edit text-warning" style="font-size: 20px;"></i>
                                    </a>

                                    <!-- Tombol Hapus -->
                                    <a href="hapus_user.php?id=<?= $berqurban['id'] ?>" onclick="return confirm('Yakin ingin menghapus user ini?');" title="Hapus User">
                                        <i class="fa fa-trash text-danger" style="font-size: 20px;"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include_once __DIR__ . '/../templates/layout.php';
?>
