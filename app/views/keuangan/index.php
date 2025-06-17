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
$judulHalaman = 'Tambah User';
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

            <!-- Toolbar row: show entries left, search and export buttons right -->
            <div class="row align-items-center mb-3" id="keuangan-toolbar-row" style="display: flex; align-items: center;">
                <div class="col-md-6 d-flex align-items-center" style="display: flex; align-items: center;">
                    <div id="keuangan-length-container"></div>
                </div>
                <div class="col-md-6 d-flex justify-content-end align-items-center" style="display: flex; justify-content: flex-end; align-items: center;">
                    <div style="max-width: 300px;">
                        <div class="input-group" style="margin-right: 16px;">
                            <input type="text" id="search-keuangan" placeholder="Cari Transaksi" class="input-sm form-control">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-sm btn-primary" id="search-keuangan-btn">Go!</button>
                            </span>
                        </div>
                    </div>
                    <div id="keuangan-export-buttons"></div>
                </div>
            </div>
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
                            <!-- <th>Aksi</th> --> <!-- Removed -->
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
                                    <!-- <td class="center">
                                        <a href="edit_keuangan.php?id=<?= $row['id_keuangan'] ?>" class="me-2">
                                            <i class="fa fa-edit text-warning"></i>
                                        </a>
                                        <a href="hapus_keuangan.php?id=<?= $row['id_keuangan'] ?>" onclick="return confirm('Yakin ingin menghapus transaksi ini?');">
                                            <i class="fa fa-trash text-danger"></i>
                                        </a>
                                    </td> -->
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data transaksi keuangan</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>


            <div class="collapse mb-3" id="tambahKeuanganForm">
                <div class="card card-body">
                    <form class="form-horizontal" action="/project-uas/api/keuangan/store" method="POST">
                        <p>Form untuk menambah transaksi keuangan baru.</p>

                        <!-- Nama Warga -->
                        <!-- <div class="form-group">
                            <label class="col-lg-2 control-label">Nama Warga</label>
                            <div class="col-lg-10">
                                <input type="text" name="nama" class="form-control" disabled value="<?php echo $_SESSION['nama'] ?>">
                            </div>
                        </div> -->

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

<style>
/* Simple, clean style for DataTables export buttons (fits Inspinia) */
.html5buttons .dt-button {
    background: #f5f5f5;
    color: #333;
    border: 1px solid #e7eaec;
    border-radius: 4px;
    padding: 6px 16px;
    margin-right: 6px;
    font-size: 13px;
    font-weight: 400;
    box-shadow: none;
    transition: background 0.2s, color 0.2s, border 0.2s;
    outline: none;
}
.html5buttons .dt-button:hover,
.html5buttons .dt-button:focus {
    background: #e7eaec;
    color: #222;
    border-color: #d2d2d2;
}

/* Align toolbar row children vertically and space them */
#keuangan-toolbar-row {
    margin-bottom: 18px;
}
#keuangan-export-buttons {
    margin-right: 12px;
    display: flex;
    align-items: center;
}
#keuangan-length-container {
    margin-right: 16px;
    display: flex;
    align-items: center;
}
.dataTables_length {
    margin-bottom: 0 !important;
}
.dataTables_length label {
    margin-bottom: 0;
    font-size: 13px;
    font-weight: 400;
    color: #333;
    display: flex;
    align-items: center;
    gap: 8px;
}
.dataTables_length select {
    border: 1px solid #e7eaec;
    border-radius: 4px;
    padding: 2px 10px;
    font-size: 12px;
    color: #333;
    background: #f9f9f9;
    margin-left: 4px;
    outline: none;
    transition: border 0.2s, box-shadow 0.2s;
    height: 28px; /* Smaller height */
}
.dataTables_length select:focus {
    border-color: #1ab394;
    background: #fff;
    box-shadow: 0 0 3px #1ab39433;
}

/* Hide default DataTables search */
.dataTables_filter {
    display: none !important;
}

/* Move DataTables export buttons into our flex container */
#keuangan-export-buttons .dt-button,
.dt-buttons .dt-button {
    background: #f5f5f5;
    color: #333;
    border: 1px solid #e7eaec;
    border-radius: 4px;
    padding: 4px 12px;
    margin-right: 6px;
    font-size: 12px;
    font-weight: 400;
    box-shadow: none;
    transition: background 0.2s, color 0.2s, border 0.2s;
    outline: none;
    height: 28px; /* Smaller height */
    line-height: 20px; /* Vertically center text/icon */
    display: inline-flex;
    align-items: center;
}
#keuangan-export-buttons .dt-button:hover,
#keuangan-export-buttons .dt-button:focus,
.dt-buttons .dt-button:hover,
.dt-buttons .dt-button:focus {
    background: #e7eaec;
    color: #222;
    border-color: #d2d2d2;
}
#keuangan-export-buttons .dt-button:last-child,
.dt-buttons .dt-button:last-child {
    margin-right: 0;
}

/* Add spacing between export buttons and search bar */
#keuangan-export-buttons {
    margin-right: 12px;
}

/* Style for custom search bar */
#search-keuangan {
    border: 1px solid #e7eaec;
    border-radius: 4px 0 0 4px;
    padding: 4px 10px;
    font-size: 12px;
    height: 28px; /* Smaller height */
    color: #333;
    background: #f9f9f9;
    outline: none;
    transition: border 0.2s, box-shadow 0.2s;
}
#search-keuangan:focus {
    border-color: #1ab394;
    background: #fff;
    box-shadow: 0 0 3px #1ab39433;
}
#search-keuangan-btn {
    border-radius: 0 4px 4px 0;
    height: 28px; /* Smaller height */
    font-size: 12px;
    padding: 4px 12px;
}
</style>

<script>
    $(document).ready(function() {
        var table = $('.dataTables-example').DataTable({
            pageLength: 25,
            responsive: true,
            dom: 'Blftip', // Show buttons, length, filter, table, info, pagination (but we'll move them)
            buttons: [
                { extend: 'copy' },
                { extend: 'csv' },
                { extend: 'excel', title: 'Data Keuangan' },
                { extend: 'pdf', title: 'Data Keuangan' },
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

        // Move export buttons into our flex container
        var $dtButtons = $('.dataTables-example').closest('.dataTables_wrapper').find('.dt-buttons');
        $('#keuangan-export-buttons').append($dtButtons);

        // Move DataTables length selector into our toolbar
        var $dtLength = $('.dataTables-example').closest('.dataTables_wrapper').find('.dataTables_length');
        $('#keuangan-length-container').append($dtLength);

        // Hide default DataTables search
        $('.dataTables-example').closest('.dataTables_wrapper').find('.dataTables_filter').hide();

        // Custom search bar logic
        $('#search-keuangan').on('input', function() {
            table.search(this.value).draw();
        });
        $('#search-keuangan-btn').on('click', function() {
            table.search($('#search-keuangan').val()).draw();
        });
        $('#search-keuangan').on('keypress', function(e) {
            if (e.which === 13) {
                table.search(this.value).draw();
            }
        });
    });
</script>