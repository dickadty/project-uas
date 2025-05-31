<?php

namespace App\Controllers;

require_once __DIR__ . '/../models/Keuangan.php';

use App\Models\Keuangan;


class KeuanganController
{
    public function index()
    {
        $data['judul'] = 'Daftar Pembagian Qurban';
        $data['keuangan'] = Keuangan::getAllUsers();

        return $data; // ← Tambahkan ini!
    }
}
