<?php
session_start();

if (isset($_SESSION['nama'])) {
} else {
    header("Location: login");
}

use App\Controllers\KeuanganController;

require_once 'config/db.php';
require_once 'app/controllers/KeuanganController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['jenis_hewan'])) {
    // Get form values
    $jenis_hewan = $_POST['jenis_hewan'] ?? '';
    $jumlah = intval($_POST['jumlah'] ?? 0);
    $keterangan = $_POST['keterangan'] ?? '';
    $tanggal_transaksi = date('Y-m-d');
    $jenis_transaksi = 'keluar';
    $id_warga = $_SESSION['id_warga'] ?? null;

    // Set harga satuan
    if (strtolower($jenis_hewan) === 'kambing') {
        $harga_satuan = 2700000;
    } elseif (strtolower($jenis_hewan) === 'sapi') {
        $harga_satuan = 21000000;
    } else {
        $harga_satuan = 0;
    }

    $total = $jumlah * $harga_satuan;
    $deskripsi = "Pembelian $jumlah $jenis_hewan. " . ($keterangan ? "Keterangan: $keterangan" : '');

    // Insert into keuangan table
    $connection = getConnection();
    $stmt = $connection->prepare("INSERT INTO keuangan (jenis_transaksi, jumlah, deskripsi, tanggal_transaksi, id_warga) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sissi", $jenis_transaksi, $total, $deskripsi, $tanggal_transaksi, $id_warga);
    $stmt->execute();
    $stmt->close();

    header("Location: /project-uas/keuangan");
    exit;
}

// Inisialisasi controll

$controllerKeuangan = new KeuanganController();

$controllerKeuangan->store();
ob_start();
?>
<?php
$content = ob_get_clean();
include_once __DIR__ . '/../templates/layout.php';
?>
