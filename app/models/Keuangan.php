<?php

namespace App\Models;

require_once __DIR__ . '/../../config/db.php';

class Keuangan
{
    public static function getAllUsers()
    {
        $connection = getConnection();
        $query = "SELECT keuangan.*, warga.nama
FROM keuangan
JOIN qurban ON keuangan.id_qurban = qurban.id_qurban
JOIN warga ON qurban.id_warga = warga.id_warga";
        $result = $connection->query($query);

        if (!$result) {
            error_log("Query Error: " . $connection->error);
            return [];
        }

        $keuangan = [];
        while ($row = $result->fetch_assoc()) {
            $keuangan[] = $row;
        }

        return $keuangan;
    }

    public static function getAllTransactions()
    {
        $connection = getConnection();
        $query = "
            SELECT k.id_keuangan, k.jenis_transaksi, k.deskripsi, k.tanggal_transaksi, 
                   w.nama AS wanama
            FROM keuangan k
            LEFT JOIN warga w ON k.id_qurban = w.id_warga";
        $result = $connection->query($query);

        if (!$result) {
            error_log("Query Error: " . $connection->error);
            return [];
        }

        $keuangan = [];
        while ($row = $result->fetch_assoc()) {
            $keuangan[] = $row;
        }

        return $keuangan;
    }
}
