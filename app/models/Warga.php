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

    public static function getByNik($nik)
    {
        $connection = getConnection();
        $query = "SELECT * FROM warga WHERE nik = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("s", $nik);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null; 
        }
    }
    public static function createWarga($nik, $nama, $no_hp, $no_rumah, $role)
    {
        $connection = getConnection();

        $is_panitia = ($role === 'panitia') ? 1 : 0;
        $is_qurban = ($role === 'berqurban') ? 1 : 0;
        $is_admin = ($role === 'admin') ? 1 : 0;
        $is_warga = ($role === 'warga') ? 1 : 0;

        $stmt = $connection->prepare(
            "INSERT INTO warga (nik, nama, no_hp, no_rumah, is_panitia, is_qurban, is_admin, is_warga) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("ssssiiii", $nik, $nama, $no_hp, $no_rumah, $is_panitia, $is_qurban, $is_admin, $is_warga);
        $stmt->execute();
        $id_warga = $stmt->insert_id;
        $stmt->close();

        return $id_warga;
    }

    public static function updateWarga($nik, $nama, $no_hp, $no_rumah, $is_panitia, $is_qurban, $is_admin, $is_warga)
    {
        $connection = getConnection();
        $query = "
            UPDATE warga 
            SET nama = ?, no_hp = ?, no_rumah = ?, is_panitia = ?, is_qurban = ?, is_admin = ?, is_warga = ?
            WHERE nik = ?
        ";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("ssssiiii", $nama, $no_hp, $no_rumah, $is_panitia, $is_qurban, $is_admin, $is_warga, $nik);
        $stmt->execute();
    }

    public static function deleteWarga($nik)
    {
        $connection = getConnection();
        $query = "DELETE FROM warga WHERE nik = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("s", $nik);
        $stmt->execute();
    }
}
