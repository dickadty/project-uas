<?php

namespace App\Controllers;

require_once __DIR__ . '/../models/PembagianQurban.php';

use App\Models\PembagianQurban;

class PembagianQurbanController
{

    public function index()
    {
        $data['judul'] = 'Daftar Pembagian Qurban';
        $data['pembagian'] = PembagianQurban::getAllUsers();
        $data['total_berat'] = PembagianQurban::getTotalBerat();
    }

    
}
