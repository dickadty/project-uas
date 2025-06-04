<?php
require_once 'config/db.php';
require_once 'app/controllers/KeuanganController.php';

use App\Controllers\KeuanganController;

// Initialize controller
$controllerKeuangan = new KeuanganController();

// Get data from controller
$keuanganData = $controllerKeuangan->index();
$totalDana = $keuanganData['totalDana'];
$keuangan = $keuanganData['keuangan'];

ob_start();
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Data Keuangan</h5>
                    <div class="ibox-content">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover dataTables-example">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Warga</th>
                                        <th>Jenis Transaksi</th>
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
                                                <td><?= htmlspecialchars($row['warga_nama']) ?></td> <!-- Menampilkan Nama Warga -->
                                                <td><?= htmlspecialchars($row['jenis_transaksi']) ?></td>
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
                                            <td colspan="6" class="text-center">Tidak ada data transaksi keuangan</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include_once __DIR__ . '/../templates/layout.php';  // Menggunakan template layout

?>

<script>
    $(document).ready(function() {
        $('.dataTables-example').DataTable({
            pageLength: 25,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [{
                    extend: 'copy'
                },
                {
                    extend: 'csv'
                },
                {
                    extend: 'excel',
                    title: 'ExampleFile'
                },
                {
                    extend: 'pdf',
                    title: 'ExampleFile'
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

<?php
