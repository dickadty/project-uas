<?php

namespace App\Controllers;

require_once __DIR__ . '/../models/Hewan.php';

use App\Models\Hewan;

class HewanController
{
    public function index()
    {
        $data['judul'] = 'Daftar Hewan Qurban';
        $data['hewan'] = Hewan::getAllUsers();
        $data['totalBerat'] = $this->getTotalBerat();

        return $data;
    }
    private function getTotalBerat()
    {
        $hewan = Hewan::getAllUsers();
        $totalBerat = 0;
        foreach ($hewan as $h) {
            $totalBerat += $h['berat'];
        }

        return $totalBerat;
    }
}
