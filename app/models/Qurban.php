<?php

namespace App\Models;

require_once __DIR__ . '/../../config/db.php';

class Qurban
{
    public static function getAllQurban() 
    {
        $connection = getConnection();
        $query = "SELECT * FROM qurban";  
        $result = $connection->query($query);

        if (!$result) {
            error_log("Query Error: " . $connection->error);
            return [];
        }

        $qurban = [];
        while ($row = $result->fetch_assoc()) {
            $qurban[] = $row;
        }

        return $qurban;
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
