<?php
session_start();

if (!isset($_SESSION['nama'])) {
    header("Location: login");
    exit();
}

require_once 'config/db.php';
require_once 'app/controllers/KeuanganController.php';
require_once 'app/controllers/WargaController.php';
require_once 'app/controllers/QurbanController.php';
require_once 'app/controllers/HewanController.php';
require_once 'app/controllers/UserController.php';

use App\Controllers\KeuanganController;
use App\Controllers\WargaController;
use App\Controllers\QurbanController;
use App\Controllers\HewanController;
use App\Controllers\UserController;

// Inisialisasi controller
$controllerKeuangan = new KeuanganController();
$controllerWarga = new WargaController();
$controllerQurban = new QurbanController();
$controllerHewan = new HewanController();
$controllerUser = new UserController();

// Ambil data dari controller
$keuanganData = $controllerKeuangan->index();
$totalDana = $keuanganData['totalDana'];
$keuangan = $keuanganData['keuangan'];

ob_start();
?>

<!-- Tabel Keuangan -->
<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Data Keuangan</h5>
        </div>
        <div class="ibox-content">
            <!-- Tombol Tambah Transaksi Keuangan -->
            <button class="btn btn-primary btn-sm mb-3" type="button" data-toggle="collapse" data-target="#tambahKeuanganForm" aria-expanded="false" aria-controls="tambahKeuanganForm">
                + Tambah Transaksi Keuangan
            </button>

            <!-- Tabel Keuangan -->
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover dataTables-example">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Warga</th>
                            <th>Jenis Transaksi</th>
                            <th>Jumlah</th>
                            <th>Deskripsi</th>
                            <th>Tanggal Transaksi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($keuangan)): ?>
                            <?php foreach ($keuangan as $i => $row): ?>
                                <tr class="gradeX">
                                    <td><?= $i + 1 ?></td>
                                    <td><?= htmlspecialchars($row['nama']) ?></td>
                                    <td><?= htmlspecialchars($row['jenis_transaksi']) ?></td>
                                    <td class="center"><?= htmlspecialchars($row['jumlah']) ?></td>
                                    <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                                    <td class="center"><?= htmlspecialchars($row['tanggal_transaksi']) ?></td>
                                    <td class="center">
                                        <a href="edit_keuangan.php?id=<?= $row['id_keuangan'] ?>" class="me-2">
                                            <i class="fa fa-edit text-warning"></i>
                                        </a>
                                        <a href="hapus_keuangan.php?id=<?= $row['id_keuangan'] ?>" onclick="return confirm('Yakin ingin menghapus transaksi ini?');">
                                            <i class="fa fa-trash text-danger"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data transaksi keuangan</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Form Tambah Transaksi Keuangan (Collapse) -->
            <div class="collapse mb-3" id="tambahKeuanganForm">
                <div class="card card-body">
                    <form class="form-horizontal" action="/api/dashboard/store_keuangan" method="POST">
                        <p>Form untuk menambah transaksi keuangan baru.</p>

                        <!-- Nama Warga -->
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Nama Warga</label>
                            <div class="col-lg-10">
                                <input type="text" name="nama" placeholder="Nama Warga" class="form-control" required>
                            </div>
                        </div>

                        <!-- Jenis Transaksi -->
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Jenis Transaksi</label>
                            <div class="col-lg-10">
                                <select name="jenis_transaksi" class="form-control" required>
                                    <option value="" disabled selected hidden>Pilih Jenis Transaksi</option>
                                    <option value="masuk">Masuk</option>
                                    <option value="keluar">Keluar</option>
                                </select>
                            </div>
                        </div>

                        <!-- Jumlah -->
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Jumlah</label>
                            <div class="col-lg-10">
                                <input type="number" name="jumlah" class="form-control" placeholder="Jumlah Dana" required>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Deskripsi</label>
                            <div class="col-lg-10">
                                <textarea name="deskripsi" class="form-control" placeholder="Deskripsi Transaksi" required></textarea>
                            </div>
                        </div>

                        <!-- Tanggal Transaksi -->
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Tanggal Transaksi</label>
                            <div class="col-lg-10">
                                <input type="date" name="tanggal_transaksi" class="form-control" required>
                            </div>
                        </div>

                        <!-- Tombol Submit -->
                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
                                <button class="btn btn-sm btn-success" type="submit">Tambah Transaksi</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include_once __DIR__ . '/../templates/layout.php';
?>

<script>
    $(document).ready(function() {
        $('.dataTables-example').DataTable({
            pageLength: 25,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                {
                    extend: 'copy'
                },
                {
                    extend: 'csv'
                },
                {
                    extend: 'excel',
                    title: 'DataKeuangan'
                },
                {
                    extend: 'pdf',
                    title: 'DataKeuangan'
                },
                {
                    extend: 'print',
                    customize: function(win) {
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');
                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    }
                }
            ]
        });
    });
</script>

