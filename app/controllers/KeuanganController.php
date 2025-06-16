<?php

namespace App\Controllers;

require_once __DIR__ . '/../models/Keuangan.php';

use App\Models\Keuangan;

class KeuanganController
{
    public function index()
    {
        $keuangan = Keuangan::getAllUsers();
        $totalDana = $this->getTotalDana();
        return ['keuangan' => $keuangan, 'totalDana' => $totalDana];
    }

    private function getTotalDana()
    {
        if (!function_exists('getConnection')) {
            throw new \Exception('Function getConnection() not found.');
        }
        $connection = getConnection();
        $query = "SELECT SUM(jumlah) AS total_dana FROM keuangan WHERE jenis_transaksi = 'masuk'";
        $result = $connection->query($query);
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['total_dana'] ?? 0;
        }
        return 0;
    }

    public function create()
    {
        $data['judul'] = 'Tambah Transaksi Keuangan';
        return $data;
    }

    // Menyimpan data transaksi keuangan
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validasi sederhana
            $nama = htmlspecialchars($_POST['nama'] ?? '');
            $jenis_transaksi = htmlspecialchars($_POST['jenis_transaksi'] ?? '');
            $jumlah = floatval($_POST['jumlah'] ?? 0);
            $deskripsi = htmlspecialchars($_POST['deskripsi'] ?? '');
            $tanggal_transaksi = htmlspecialchars($_POST['tanggal_transaksi'] ?? '');

            if ($nama && $jenis_transaksi && $jumlah > 0 && $tanggal_transaksi) {
                Keuangan::createKeuangan($nama, $jenis_transaksi, $jumlah, $deskripsi, $tanggal_transaksi);
                header('Location: /keuangan');
                exit;
            } else {
                // Handle error, misal redirect ke form dengan pesan error
                header('Location: /keuangan/create?error=invalid');
                exit;
            }
        }
    }

    // Menampilkan halaman edit transaksi keuangan
    public function edit($id)
    {
        $data['keuangan'] = Keuangan::getKeuanganById($id);
        if (!$data['keuangan']) {
            // Handle jika data tidak ditemukan
            header('Location: /keuangan?error=notfound');
            exit;
        }
        $data['judul'] = 'Edit Transaksi Keuangan';
        return $data;
    }

    // Memperbarui data transaksi keuangan
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama = htmlspecialchars($_POST['nama'] ?? '');
            $jenis_transaksi = htmlspecialchars($_POST['jenis_transaksi'] ?? '');
            $jumlah = floatval($_POST['jumlah'] ?? 0);
            $deskripsi = htmlspecialchars($_POST['deskripsi'] ?? '');
            $tanggal_transaksi = htmlspecialchars($_POST['tanggal_transaksi'] ?? '');

            if ($nama && $jenis_transaksi && $jumlah > 0 && $tanggal_transaksi) {
                Keuangan::updateKeuangan($id, $nama, $jenis_transaksi, $jumlah, $deskripsi, $tanggal_transaksi);
                header('Location: /keuangan');
                exit;
            } else {
                header("Location: /keuangan/edit/$id?error=invalid");
                exit;
            }
        }
    }

    // Menghapus transaksi keuangan
    public function delete($id)
    {
        Keuangan::deleteKeuangan($id);
        header('Location: /keuangan');
        exit;
    }
}