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
        $connection = getConnection();
        $query = "SELECT SUM(jumlah) AS total_dana FROM keuangan WHERE jenis_transaksi = 'masuk'";
        $result = $connection->query($query);

        if ($result) {
            $row = $result->fetch_assoc();
            return $row['total_dana'];
        }

        return 0;  
    }
}
