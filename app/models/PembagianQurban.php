<?php

namespace App\Models;

require_once __DIR__ . '/../../config/db.php';

class PembagianQurban
{
    public static function getAllUsers()
    {
        $connection = getConnection();
        $query = "SELECT * FROM pembagian_qurban";
        $result = $connection->query($query);

        if (!$result) {
            error_log("Query Error: " . $connection->error);
            return [];
        }

        $pembagian_qurban = [];
        while ($row = $result->fetch_assoc()) {
            $pembagian_qurban[] = $row;
        }

        return $pembagian_qurban;
    }

    public static function getTotalBerat()
    {
        $connection = getConnection();
        $query = "
            SELECT SUM(h.berat * p.jumlah_kg) AS total_berat
            FROM pembagian_qurban p
            JOIN hewan h ON p.id_hewan = h.id_hewan
        ";
        $result = $connection->query($query);

        if (!$result) {
            error_log("Query Error: " . $connection->error);
            return 0;
        }

        $row = $result->fetch_assoc();
        return $row['total_berat'] ?? 0;
    }

    public static function createPembagianQurban($id_warga, $id_qurban, $jumlah_kg)
    {
        $connection = getConnection();
        $status_ambil = 0;
        $qr_token = uniqid('qr_', true);

        $stmt = $connection->prepare(
            "INSERT INTO pembagian_qurban (id_warga, id_qurban, jumlah_kg, status_ambil, qr_token)
            VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("iiiss", $id_warga, $id_qurban, $jumlah_kg, $status_ambil, $qr_token);
        $stmt->execute();
        $stmt->close();
    }
}
