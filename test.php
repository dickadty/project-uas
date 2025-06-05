    <?php
    if (strpos($_SERVER['REQUEST_URI'], 'dashboard/create') !== false): ?>
        
    <?php endif; ?>


    if router


    // PembagianQurban
    <?php

namespace App\Controllers;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../models/PembagianQurban.php';

use App\Models\PembagianQurban;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;


class PembagianQurbanController
{
    public function index()
    {
        $data['judul'] = 'Daftar Pembagian Qurban';
        $data['pembagian'] = PembagianQurban::getAllUsers();  // Mengambil data dari model
        return $data;
    }

    // Menyimpan pembagian qurban dan menghasilkan QR Code
    public static function savePembagianQurban($idWarga, $idQurban, $jumlahKg, $qrToken)
    {
        $connection = getConnection(); // Pastikan ini mengembalikan koneksi database
        $query = "INSERT INTO pembagian_qurban (id_warga, id_qurban, jumlah_kg, qr_token, status_ambil) 
                  VALUES (?, ?, ?, ?, ?)";

        $statusAmbil = 0;  // status pengambilan (belum diambil)
        $stmt = $connection->prepare($query);
        $stmt->bind_param("iiiss", $idWarga, $idQurban, $jumlahKg, $qrToken, $statusAmbil);
        $stmt->execute();
        $stmt->close();
    }

    // Mendapatkan pembagian qurban berdasarkan ID
    public static function getPembagianById($idPembagian)
    {
        $connection = getConnection();
        $query = "SELECT * FROM pembagian_qurban WHERE id_pembagian = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("i", $idPembagian);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();

        return $data;
    }

    // Membuat dan menyimpan QR Code
    public static function generateQrCode($idWarga, $idQurban, $jumlahKg)
    {
        $qrToken = uniqid('qr_', true);
        self::savePembagianQurban($idWarga, $idQurban, $jumlahKg, $qrToken);

        $qrCode = new QrCode($qrToken);
        $qrCode->getSize(300); // Ukuran QR Code
        $qrCode->getErrorCorrectionLevel(ErrorCorrectionLevel::Low); // Tingkat koreksi kesalahan

        // Tentukan lokasi file QR Code
        $filePath = __DIR__ . '/../../public/qr_codes/' . $qrToken . '.png';

        // Cek jika direktori qr_codes ada, jika tidak buat direktori
        if (!is_dir(__DIR__ . '/../../public/qr_codes')) {
            mkdir(__DIR__ . '/../../public/qr_codes', 0777, true);
        }

        // Menyimpan QR Code dalam format PNG
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        // Menyimpan QR Code ke file
        file_put_contents($filePath, $result->getString());

        // Mengembalikan path untuk digunakan dalam tampilan
        return '/qr_codes/' . $qrToken . '.png';
    }

    // Fungsi untuk memperbarui status pengambilan
    public static function updateStatusAmbil($idPembagian, $status)
    {
        $connection = getConnection();
        $query = "UPDATE pembagian_qurban SET status_ambil = ? WHERE id_pembagian = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("ii", $status, $idPembagian);
        $stmt->execute();
        $stmt->close();
    }
}


model
<?php

namespace App\Models;

require_once __DIR__ . '/../../config/db.php';

class PembagianQurban
{
    // Mengambil semua data pembagian qurban
    public static function getAllUsers()
    {
        $connection = getConnection();
        $query = "SELECT * FROM pembagian_qurban";
        $result = $connection->query($query);

        if (!$result) {
            error_log("Query Error: " . $connection->error);
            return [];
        }

        $pembagian_qurban = [];
        while ($row = $result->fetch_assoc()) {
            $pembagian_qurban[] = $row;
        }

        return $pembagian_qurban;
    }
    public static function getPembagianById($idPembagian)
    {
        $connection = getConnection();
        $query = "SELECT * FROM pembagian_qurban WHERE id_pembagian = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("i", $idPembagian);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();

        return $data;
    }

    public static function savePembagianQurban($idWarga, $idQurban, $jumlahKg, $qrToken)
    {
        $connection = getConnection();
        $query = "INSERT INTO pembagian_qurban (id_warga, id_qurban, jumlah_kg, qr_token, status_ambil) 
                  VALUES (?, ?, ?, ?, ?)";
        $statusAmbil = 0;

        $stmt = $connection->prepare($query);
        $stmt->bind_param("iiiss", $idWarga, $idQurban, $jumlahKg, $qrToken, $statusAmbil);
        $stmt->execute();
        $stmt->close();
    }

    public static function updateStatusAmbil($idPembagian, $status)
    {
        $connection = getConnection();
        $query = "UPDATE pembagian_qurban SET status_ambil = ? WHERE id_pembagian = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("ii", $status, $idPembagian);
        $stmt->execute();
        $stmt->close();
    }
}
