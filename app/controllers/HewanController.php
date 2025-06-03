<?php

namespace App\Controllers;

require_once __DIR__ . '/../models/Hewan.php';

use App\Models\Hewan;

class HewanController
{
    public function index()
    {
        // Data judul halaman
        $data['judul'] = 'Daftar Hewan Qurban';

        // Mengambil data semua hewan dari model Hewan
        $data['hewan'] = Hewan::getAllUsers();

        // Menghitung total berat hewan
        $data['totalBerat'] = $this->getTotalBerat();

        return $data;
    }

    // Fungsi untuk menghitung total berat semua hewan
    private function getTotalBerat()
    {
        // Mengambil semua data hewan dari model
        $hewan = Hewan::getAllUsers();
        $totalBerat = 0;

        // Menambahkan berat setiap hewan ke total berat
        foreach ($hewan as $h) {
            $totalBerat += $h['berat'];
        }

        return $totalBerat;  // Mengembalikan total berat
    }
}
