<?php

namespace App\Models;

require_once __DIR__ . '/../../config/db.php';

class Qurban
{
    public static function getAllUsers()
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

    
}
