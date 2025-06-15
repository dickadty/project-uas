<?php

namespace App\Models;
use Exception;

require_once __DIR__ . '/../../config/db.php';

class User
{
    public static function getAllUsers($page = 1, $limit = 10)
    {
        $connection = getConnection();
        $offset = ($page - 1) * $limit;

        $query = "
            SELECT u.id_users, u.nik, u.role, w.nama
            FROM users u
            LEFT JOIN warga w ON w.nik = u.nik
            LIMIT ? OFFSET ?
        ";

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

    public static function getByRole($role, $page = 1, $limit = 10)
    {
        $connection = getConnection();
        $offset = ($page - 1) * $limit;
        $query = "
            SELECT u.id_users, u.nik, u.role, w.nama
            FROM users u
            LEFT JOIN warga w ON w.nik = u.nik
            WHERE u.role = ?
            LIMIT ? OFFSET ?
        ";

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

    public static function createUser($nik, $role, $password)
    {
        $connection = getConnection();
        if (!in_array($role, ['admin', 'panitia', 'warga', 'berqurban'])) {
            throw new Exception('Invalid role');
        }
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $query = "INSERT INTO users (nik, role, password) VALUES (?, ?, ?)";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("sss", $nik, $role, $hashedPassword);
        $stmt->execute();
    }

    public static function update($id, $nik, $role, $password)
    {
        $connection = getConnection();
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $query = "UPDATE users SET nik = ?, role = ?, password = ? WHERE id_users = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("sssi", $nik, $role, $hashedPassword, $id);
        $stmt->execute();
    }

    public static function delete($id)
    {
        $connection = getConnection();
        $query = "DELETE FROM users WHERE id_users = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
}
