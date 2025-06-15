<?php

namespace App\Controllers;

require_once __DIR__ . '/../models/Warga.php';

use App\Models\Warga;

class WargaController
{
    public function index()
    {
        $data['judul'] = 'Daftar Pembagian Qurban';
        $data['warga'] = Warga::getAllUsers();
        return $data; 
    }
}
