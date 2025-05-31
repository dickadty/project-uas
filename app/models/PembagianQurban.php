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
}
