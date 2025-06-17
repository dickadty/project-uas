<?php

namespace App\Models;

require_once __DIR__ . '/../../config/db.php';

class Qurban
{
    public static function getAllQurban() 
    {
        $connection = getConnection();
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

        if (!$result) {
            error_log("Query Error: " . $connection->error);
            return 0;
        }

        $total_qurban = 0;
        while ($row = $result->fetch_assoc()) {
            $total_qurban += (int)$row['jumlah_qurban'];
        }

        return $total_qurban;
    }

    public static function getIdHewanByJenis($jenis_hewan)
    {
        $connection = getConnection();
        $stmt = $connection->prepare("SELECT id_hewan FROM hewan WHERE jenis_hewan = ? LIMIT 1");
        $stmt->bind_param("s", $jenis_hewan);
        $stmt->execute();
        $stmt->bind_result($id_hewan);
        $stmt->fetch();
        $stmt->close();

        return $id_hewan;
    }

    public static function createQurban($id_hewan, $id_warga)
    {
        $connection = getConnection();
        $stmt = $connection->prepare(
            "INSERT INTO qurban (id_hewan, id_warga, status_pembayaran) 
             VALUES (?, ?, 'belum selesai')"
        );
        $stmt->bind_param("ii", $id_hewan, $id_warga);
        $stmt->execute();
        $qurbanId = $stmt->insert_id; 
        $stmt->close();

        return $qurbanId;
    }
}
