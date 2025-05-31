<?php

namespace App\Models;

require_once __DIR__ . '/../../config/db.php';

class Keuangan
{
    public static function getAllUsers()
    {
        $connection = getConnection();
        $query = "SELECT * FROM keuangan";
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
