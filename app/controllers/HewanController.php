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
        // Ambil data qurban dengan jumlah_qurban dan berat per jenis
        $connection = \getConnection();
        $query = "
            SELECT 
                q.id_hewan,
                h.jenis_hewan,
                h.berat,
                COUNT(*) AS jumlah_peserta,
                CASE 
                    WHEN q.id_hewan = 1 THEN CEIL(COUNT(*) / 7)
                    ELSE COUNT(*)
                END AS jumlah_qurban
            FROM qurban q
            JOIN hewan h ON q.id_hewan = h.id_hewan
            GROUP BY q.id_hewan
        ";
        $result = $connection->query($query);

        $totalBerat = 0;
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $totalBerat += ((int)$row['jumlah_qurban']) * ((float)$row['berat']);
            }
        }
        return $totalBerat;
    }
}
