<?php

namespace App\Models;

require_once __DIR__ . '/../../config/db.php';

class User
{
    // Fungsi untuk mengambil semua user dengan pagination
    public static function getAllUsers($page = 1, $limit = 10)
    {
        $connection = getConnection();

        // Menghitung offset untuk pagination
        $offset = ($page - 1) * $limit;

        // Query untuk mengambil semua user dengan limit dan offset
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

    // Fungsi untuk mendapatkan total jumlah pengguna untuk pagination
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

    // Fungsi untuk mendapatkan user berdasarkan role
    public static function getByRole($role, $page = 1, $limit = 10)
    {
        $connection = getConnection();

        // Menghitung offset untuk pagination
        $offset = ($page - 1) * $limit;

        // Query untuk mengambil user berdasarkan role
        $stmt = $connection->prepare("SELECT * FROM users WHERE role = ? LIMIT ? OFFSET ?");
        $stmt->bind_param("sii", $role, $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();

        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        return $users;
    }
}
