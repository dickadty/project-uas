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

        // Query untuk mengambil semua user dengan nama dari tabel warga menggunakan JOIN
        $query = "
            SELECT u.id_users, u.nik, u.role, w.nama
            FROM users u
            LEFT JOIN warga w ON w.nik = u.nik
            LIMIT ? OFFSET ?
        ";
        
        // Menggunakan prepared statement untuk menghindari SQL Injection
        $stmt = $connection->prepare($query);
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();

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

    // Fungsi untuk mendapatkan user berdasarkan role dengan pagination
    public static function getByRole($role, $page = 1, $limit = 10)
    {
        $connection = getConnection();

        // Menghitung offset untuk pagination
        $offset = ($page - 1) * $limit;

        // Query untuk mengambil user berdasarkan role dengan nama dari tabel warga
        $query = "
            SELECT u.id_users, u.nik, u.role, w.nama
            FROM users u
            LEFT JOIN warga w ON w.nik = u.nik
            WHERE u.role = ?
            LIMIT ? OFFSET ?
        ";
        
        // Menggunakan prepared statement untuk menghindari SQL Injection
        $stmt = $connection->prepare($query);
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
