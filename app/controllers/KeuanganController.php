<?php

namespace App\Controllers;

require_once __DIR__ . '/../models/Keuangan.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use App\Models\Keuangan;

class KeuanganController
{
    public function index()
    {
        $keuangan = Keuangan::getAllKeuangan();
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

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $jenis_transaksi = htmlspecialchars($_POST['jenis_transaksi'] ?? '');
                $jumlah = floatval($_POST['jumlah'] ?? 0);
                $deskripsi = htmlspecialchars($_POST['deskripsi'] ?? '');
                $tanggal_transaksi = htmlspecialchars($_POST['tanggal_transaksi'] ?? '');
                $nama = htmlspecialchars($_SESSION['nama'] ?? '');
                if ($jenis_transaksi && $jumlah > 0 && $tanggal_transaksi) {
                    Keuangan::createKeuangan($nama, $jenis_transaksi, $jumlah, $deskripsi, $tanggal_transaksi);
                    header('Location: /project-uas/keuangan');
                    exit;
                } else {
                    header('Location: /keuangan/create?error=invalid');
                    exit;
                }
            } catch (\Throwable $e) {
                var_dump($e->getMessage());
                exit;
            }
        }
    }

    public function edit($id)
    {
        $data['keuangan'] = Keuangan::getKeuanganById($id);
        if (!$data['keuangan']) {
            header('Location: /keuangan?error=notfound');
            exit;
        }
        $data['judul'] = 'Edit Transaksi Keuangan';
        return $data;
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $jenis_transaksi = htmlspecialchars($_POST['jenis_transaksi'] ?? '');
            $jumlah = floatval($_POST['jumlah'] ?? 0);
            $deskripsi = htmlspecialchars($_POST['deskripsi'] ?? '');
            $tanggal_transaksi = htmlspecialchars($_POST['tanggal_transaksi'] ?? '');

            if ($jenis_transaksi && $jumlah > 0 && $tanggal_transaksi) {
                Keuangan::updateKeuangan($id, $jenis_transaksi, $jumlah, $deskripsi, $tanggal_transaksi);
                header('Location: /keuangan');
                exit;
            } else {
                header("Location: /keuangan/edit/$id?error=invalid");
                exit;
            }
        }
    }

    public function delete($id)
    {
        Keuangan::deleteKeuangan($id);
        header('Location: /keuangan');
        exit;
    }
}
