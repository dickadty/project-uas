<?php
require_once 'config/db.php';
require_once 'app/controllers/UserController.php';

use App\Controllers\UserController;

$controller = new UserController();
$users = $controller->index();
ob_start();
?>
<div class="row">
    <div class="col-lg-3">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Total Warga</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins">60</h1>
                <div class="stat-percent font-bold text-success">98% <i class="fa fa-bolt"></i></div>
                <small>Total income</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Total Dana</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins">Rp 2.000.000</h1>
                <div class="stat-percent font-bold text-info">20% <i class="fa fa-level-up"></i></div>
                <small>New orders</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Hewan Qurban</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins">106,120</h1>
                <div class="stat-percent font-bold text-navy">44% <i class="fa fa-level-up"></i></div>
                <small>New visits</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="ibox float-e-margins">
            <div class="ibox-title">

                <h5>Total Daging</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins">100 Kg</h1>
                <div class="stat-percent font-bold text-danger">38% <i class="fa fa-level-down"></i></div>
                <small>In first month</small>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Daftar User</h5>
            <div class="ibox-tools">
                <a class="collapse-link">
                    <i class="fa fa-chevron-up"></i>
                </a>
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-wrench"></i>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li><a href="#">Pengaturan 1</a></li>
                    <li><a href="#">Pengaturan 2</a></li>
                </ul>
                <a class="close-link">
                    <i class="fa fa-times"></i>
                </a>
            </div>
        </div>
        <div class="ibox-content">
            <!-- Filter dan Pencarian -->
            <div class="row mb-3">
                <div class="col-sm-9 m-b-xs">
                    <div data-toggle="buttons" class="btn-group">
                        <label class="btn btn-sm btn-white active">
                            <input type="radio" name="filter" id="semua" checked> Semua
                        </label>
                        <label class="btn btn-sm btn-white">
                            <input type="radio" name="filter" id="admin"> Admin
                        </label>
                        <label class="btn btn-sm btn-white">
                            <input type="radio" name="filter" id="user"> User
                        </label>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="input-group">
                        <input type="text" placeholder="Cari User" class="input-sm form-control">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-sm btn-primary">Go!</button>
                        </span>
                    </div>
                </div>
            </div>
            <a href="tambah_user.php" class="btn btn-primary btn-sm mb-3">+ Tambah User</a>

            <!-- Tabel User -->
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($users['users'])): ?>
                            <?php foreach ($users['users'] as $i => $row): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><?= htmlspecialchars($row['nama']) ?></td>
                                    <td><?= htmlspecialchars(ucwords($row['role'])) ?></td>

                                    <td>
                                        <a href="edit_user.php?id=<?= $row['id_users'] ?>" class="me-2">
                                            <i class="fa fa-edit text-warning"></i>
                                        </a>
                                        <a href="hapus_user.php?id=<?= $row['id_users'] ?>" onclick="return confirm('Yakin ingin menghapus user ini?');">
                                            <i class="fa fa-trash text-danger"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data user</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Paginasi -->
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <!-- Tombol Previous -->
                    <li class="page-item <?= $users['currentPage'] == 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $users['currentPage'] - 1 ?>">Previous</a>
                    </li>

                    <!-- Nomor Halaman -->
                    <?php for ($i = 1; $i <= $users['totalPages']; $i++): ?>
                        <li class="page-item <?= $users['currentPage'] == $i ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <!-- Tombol Next -->
                    <li class="page-item <?= $users['currentPage'] == $users['totalPages'] ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $users['currentPage'] + 1 ?>">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include_once __DIR__ . '/../templates/layout.php';
?>