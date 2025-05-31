<?php

namespace App\Models;

require_once __DIR__ . '/../../config/db.php';

class User
{
    public static function getAllUsers($page = 1, $limit = 10)
    {
        $connection = getConnection();

        // Menghitung offset
        $offset = ($page - 1) * $limit;

        // Mengubah query untuk menggunakan LIMIT dan OFFSET
        $query = "SELECT * FROM users LIMIT $limit OFFSET $offset";
        $result = $connection->query($query);

        if (!$result) {
            error_log("Query Error: " . $connection->error);
            return [];
        }

        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        return $users;
    }

    // Menambahkan method untuk mendapatkan total pengguna untuk perhitungan pagination
    public static function getTotalUsers()
    {
        $connection = getConnection();
        $query = "SELECT COUNT(*) as total FROM users";
        $result = $connection->query($query);

        if (!$result) {
            error_log("Query Error: " . $connection->error);
            return 0;
        }

        $row = $result->fetch_assoc();
        return $row['total'];
    }
}
