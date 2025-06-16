<?php
session_start();

if (isset($_SESSION['nama'])) {} else {header("Location: login");}

require_once 'config/db.php';
require_once 'app/controllers/KeuanganController.php';

use App\Controllers\KeuanganController;

$judulHalaman = 'Tambah Transaksi Keuangan';

ob_start();
?>

<div class="col-lg-5">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Tambah Transaksi Keuangan</h5>
        </div>
        <div class="ibox-content">
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
                        <button class="btn btn-sm btn-white" type="submit">Tambah Transaksi</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include_once __DIR__ . '/../templates/layout.php';
?>
