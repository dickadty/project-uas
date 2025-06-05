<?php
require_once 'app/controllers/PembagianQurbanController.php';

use App\Controllers\PembagianQurbanController;

$pembagian = new PembagianQurbanController();

ob_start();
?>

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title d-flex justify-content-between align-items-center">
                <h5>Status Pengambilan Daging</h5>
                <a href="#" class="btn btn-sm btn-dark">Atur Ulang Pembagian</a>
            </div>
            <div class="ibox-content">

                <!-- Contoh statis, nanti bisa diganti loop -->
                <div class="card-status d-flex justify-content-between align-items-center border rounded p-3 mb-3">
                    <div>
                        <h5 class="fw-bold mb-1">Warga 1</h5>
                        <p class="text-muted mb-0">2 Kg - Kategori: Warga</p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-dark rounded-pill px-3 py-2">Sudah Diambil</span>
                        <a href="#" class="btn btn-outline-dark btn-sm">Ubah Status</a>
                    </div>
                </div>

                <div class="card-status d-flex justify-content-between align-items-center border rounded p-3 mb-3">
                    <div>
                        <h5 class="fw-bold mb-1">Warga 2</h5>
                        <p class="text-muted mb-0">2 Kg - Kategori: Warga</p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-secondary rounded-pill px-3 py-2">Belum Diambil</span>
                        <a href="#" class="btn btn-outline-dark btn-sm">Ubah Status</a>
                    </div>
                </div>

                <div class="card-status d-flex justify-content-between align-items-center border rounded p-3 mb-3">
                    <div>
                        <h5 class="fw-bold mb-1">Warga 3</h5>
                        <p class="text-muted mb-0">2 Kg - Kategori: Warga</p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-secondary rounded-pill px-3 py-2">Belum Diambil</span>
                        <a href="#" class="btn btn-outline-dark btn-sm">Ubah Status</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include_once __DIR__ . '/../templates/layout.php';
?>
