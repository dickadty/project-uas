<?php

namespace App\Models;

require_once __DIR__ . '/../../config/db.php';

class Warga
{
    public static function getAllUsers()
    {
        $connection = getConnection();
        $query = "SELECT * FROM warga";
        $result = $connection->query($query);

        if (!$result) {
            error_log("Query Error: " . $connection->error);
            return [];
        }

        $warga = [];
        while ($row = $result->fetch_assoc()) {
            $warga[] = $row;
        }

        return $warga;
    }
}
