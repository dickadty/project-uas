<?php

namespace App\Controllers;

require_once __DIR__ . '/../models/Qurban.php';

use App\Models\Qurban;

class QurbanController
{
    public function index()
    {
        $data['judul'] = 'Daftar Pembagian Qurban';
        $data['qurban'] = Qurban::getAllQurban();  
        return $data;
    }
}
