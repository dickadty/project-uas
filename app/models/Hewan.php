<?php

namespace App\Models;

require_once __DIR__ . '/../../config/db.php';

class Hewan
{
    public static function getAllUsers()
    {
        $connection = getConnection();
        $query = "SELECT * FROM hewan";
        $result = $connection->query($query);

        if (!$result) {
            error_log("Query Error: " . $connection->error);
            return [];
        }

        $hewan = [];
        while ($row = $result->fetch_assoc()) {
            $hewan[] = $row;
        }

        return $hewan;
    }
}
