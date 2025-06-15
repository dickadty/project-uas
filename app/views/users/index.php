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
use App\Controllers\KeuanganController;
use App\Controllers\WargaController;
use App\Controllers\QurbanController;
use App\Controllers\HewanController;


// Initialize controllers
$controllerKeuangan = new KeuanganController();
$controllerWarga = new WargaController();
$controllerQurban = new QurbanController();
$controllerHewan = new HewanController();
$controllerUser = new UserController();

// Get data from controllers
$keuanganData = $controllerKeuangan->index();
$wargaData = $controllerWarga->index();
$qurbanData = $controllerQurban->index();
$hewanData = $controllerHewan->index();
$users = $controllerUser->index();

// Extract data for display
$totalDana = $keuanganData['totalDana'];
$totalWarga = count($wargaData['warga']);
$totalQurban = count($qurbanData['qurban']);
$totalDaging = $hewanData['totalBerat'];
ob_start();
?>
<div class="row">
    <!-- Total Warga -->
    <div class="col-lg-3">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Total Warga</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins"><?= $totalWarga ?></h1> <!-- Display total warga -->
                <div class="stat-percent font-bold text-success">98% <i class="fa fa-bolt"></i></div>
                <small>Total warga terdaftar</small>
            </div>
        </div>
    </div>

    <!-- Total Dana -->
    <div class="col-lg-3">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Total Dana</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins">Rp <?= number_format($totalDana, 2, ',', '.') ?></h1>
                <div class="stat-percent font-bold text-info">20% <i class="fa fa-level-up"></i></div>
                <small>Total dana masuk</small>
            </div>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Total Qurban</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins"><?= $totalQurban ?></h1> 
                <div class="stat-percent font-bold text-info">20% <i class="fa fa-level-up"></i></div>
                <small>Total qurban terdaftar</small>
            </div>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Total Daging</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins"><?= $totalDaging ?> Kg</h1> <!-- Display total berat daging -->
                <div class="stat-percent font-bold text-danger">38% <i class="fa fa-level-down"></i></div>
                <small>Total berat daging (hewan qurban)</small>
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
            <?php if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'panitia'): ?>
            <div class="row mb-3">
                <div class="col-sm-9 m-b-xs">
                    <div data-toggle="buttons" class="btn-group" id="role-filter-group">
                        <label class="btn btn-sm btn-white active" data-role="semua">
                            <input type="radio" name="filter" id="semua" autocomplete="off" checked> Semua
                        </label>
                        <label class="btn btn-sm btn-white" data-role="administrator">
                            <input type="radio" name="filter" id="administrator" autocomplete="off"> Admin
                        </label>
                        <label class="btn btn-sm btn-white" data-role="panitia">
                            <input type="radio" name="filter" id="panitia" autocomplete="off"> Panitia
                        </label>
                        <label class="btn btn-sm btn-white" data-role="berqurban">
                            <input type="radio" name="filter" id="berqurban" autocomplete="off"> Berqurban
                        </label>
                        <label class="btn btn-sm btn-white" data-role="warga">
                            <input type="radio" name="filter" id="warga" autocomplete="off"> Warga
                        </label>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="input-group">
                        <input type="text" id="search-user" placeholder="Cari User" class="input-sm form-control">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-sm btn-primary" id="search-user-btn">Go!</button>
                        </span>
                    </div>
                </div>
            </div>
            <button class="btn btn-primary btn-sm mb-3" type="button" data-toggle="collapse" data-target="#tambahUserForm" aria-expanded="false" aria-controls="tambahUserForm">
                + Tambah User
            </button>

            <!-- Form Tambah User (Collapse) -->
            <div class="collapse mb-3" id="tambahUserForm">
                <div class="card card-body">
                    <form class="form-horizontal" action="/project-uas/api/dashboard/store" method="POST">
                        <p>Form untuk menambah user baru.</p>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Nama</label>
                            <div class="col-lg-10">
                                <input type="text" name="nama" placeholder="Nama" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">NIK</label>
                            <div class="col-lg-10">
                                <input type="text" name="nik" class="form-control" placeholder="NIK" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">No. HP</label>
                            <div class="col-lg-10">
                                <input type="text" name="no_hp" class="form-control" placeholder="No. HP" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">No. Rumah</label>
                            <div class="col-lg-10">
                                <input type="text" name="no_rumah" class="form-control" placeholder="No. Rumah, e.g. A4 504" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Role</label>
                            <div class="col-lg-10">
                                <select name="role" class="form-control" id="role-tambah-user" required>
                                    <option value="">Daftar untuk bagian...</option>
                                    <option value="warga">Warga</option>
                                    <option value="berqurban">Berqurban</option>
                                    <option value="panitia">Panitia</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" id="hewan-qurban-group-tambah-user" style="display: none;">
                            <label class="col-lg-2 control-label">Jenis Hewan</label>
                            <div class="col-lg-10">
                                <select class="form-control" name="jenis_hewan" id="jenis_hewan_tambah_user">
                                    <option value="" disabled selected hidden>Pilih Hewan Qurban</option>
                                    <option value="kambing">Kambing Rp 2.750.000</option>
                                    <option value="sapi">Sapi Rp 3.010.000</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Password</label>
                            <div class="col-lg-10">
                                <input type="password" name="password" placeholder="Password" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
                                <button class="btn btn-sm btn-success" type="submit">Tambah User</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var roleSelect = document.getElementById('role-tambah-user');
                    var hewanGroup = document.getElementById('hewan-qurban-group-tambah-user');
                    var jenisHewan = document.getElementById('jenis_hewan_tambah_user');

                    function toggleHewanGroup() {
                        if (roleSelect.value === 'berqurban') {
                            hewanGroup.style.display = '';
                            jenisHewan.setAttribute('required', 'required');
                        } else {
                            hewanGroup.style.display = 'none';
                            jenisHewan.removeAttribute('required');
                        }
                    }

                    roleSelect.addEventListener('change', toggleHewanGroup);
                    // Trigger on load (for autofill or edit)
                    toggleHewanGroup();
                });

                document.addEventListener('DOMContentLoaded', function() {
                    // Role filter and search
                    var filterGroup = document.getElementById('role-filter-group');
                    var tableRows = document.querySelectorAll('table tbody tr');
                    var searchInput = document.getElementById('search-user');
                    var searchBtn = document.getElementById('search-user-btn');

                    function normalizeRole(role) {
                        // Map DB role to display label for filtering
                        switch (role.toLowerCase()) {
                            case 'admin': return 'administrator';
                            default: return role.toLowerCase();
                        }
                    }

                    function filterTable() {
                        var selectedRole = filterGroup.querySelector('label.btn.active').getAttribute('data-role');
                        var searchValue = searchInput.value.trim().toLowerCase();

                        tableRows.forEach(function(row) {
                            // If no user data row, skip (e.g. "Tidak ada data user")
                            var roleCell = row.querySelector('td:nth-child(3)');
                            var namaCell = row.querySelector('td:nth-child(2)');
                            if (!roleCell || !namaCell) return;

                            var role = normalizeRole(roleCell.textContent.trim());
                            var nama = namaCell.textContent.trim().toLowerCase();

                            var roleMatch = (selectedRole === 'semua') || (role === selectedRole);
                            var searchMatch = !searchValue || nama.includes(searchValue);

                            if (roleMatch && searchMatch) {
                                row.style.display = '';
                            } else {
                                row.style.display = 'none';
                            }
                        });
                    }

                    // Handle filter button click
                    filterGroup.querySelectorAll('label.btn').forEach(function(label) {
                        label.addEventListener('click', function() {
                            filterGroup.querySelectorAll('label.btn').forEach(function(l) {
                                l.classList.remove('active');
                            });
                            label.classList.add('active');
                            filterTable();
                        });
                    });

                    // Handle search
                    searchInput.addEventListener('input', filterTable);
                    searchBtn.addEventListener('click', filterTable);

                    // Initial filter
                    filterTable();
                });
            </script>
            <!-- Tabel User -->
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
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
                                        <a href="dashboard/delete?id=<?= $row['id_users'] ?>" onclick="return confirm('Yakin ingin menghapus user ini?');">
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
            <?php endif; ?>
        </div>
    </div>
</div>
<?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Panitia'): ?>
<button class="btn btn-primary btn-sm mb-3" type="button" data-toggle="collapse" data-target="#tambahAlatForm" aria-expanded="false" aria-controls="tambahAlatForm">
    + Tambah Pembelian Alat
</button>

<!-- Form Tambah Pembelian Alat (Collapse) -->
<div class="collapse mb-3" id="tambahAlatForm">
    <div class="card card-body">
        <form class="form-horizontal" action="dashboard/store_alat" method="POST">
            <p>Form untuk mencatat pembelian alat qurban.</p>
            <div class="form-group">
                <label class="col-lg-3 control-label">Nama Alat</label>
                <div class="col-lg-9">
                    <select name="nama_alat" class="form-control" required>
                        <option value="" disabled selected hidden>Pilih alat...</option>
                        <option value="Pisau Sembelih">Pisau Sembelih</option>
                        <option value="Talenan Besar">Talenan Besar</option>
                        <option value="Timbangan Daging">Timbangan Daging</option>
                        <option value="Plastik Kemasan">Plastik Kemasan</option>
                        <option value="Sarung Tangan">Sarung Tangan</option>
                        <option value="Masker">Masker</option>
                        <option value="Ember/Bak Penampung">Ember/Bak Penampung</option>
                        <option value="Tali Tambang">Tali Tambang</option>
                        <option value="Apron/Penutup Badan">Apron/Penutup Badan</option>
                        <option value="Alat Tulis">Alat Tulis</option>
                        <option value="Sabun Cuci Tangan">Sabun Cuci Tangan</option>
                        <option value="Disinfektan">Disinfektan</option>
                        <option value="Kantong Sampah">Kantong Sampah</option>
                        <option value="Gunting">Gunting</option>
                        <option value="Senter/Headlamp">Senter/Headlamp</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Jumlah</label>
                <div class="col-lg-9">
                    <input type="number" name="jumlah" class="form-control" min="1" required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Harga Satuan (Rp)</label>
                <div class="col-lg-9">
                    <input type="number" name="harga_satuan" class="form-control" min="0" required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Keterangan</label>
                <div class="col-lg-9">
                    <input type="text" name="keterangan" class="form-control" placeholder="Opsional">
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-offset-3 col-lg-9">
                    <button class="btn btn-sm btn-success" type="submit">Tambah Pembelian</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>
<?php
$content = ob_get_clean();
include_once __DIR__ . '/../templates/layout.php';
?>
