<?php

namespace App\Models;

require_once __DIR__ . '/../../config/db.php';

class Keuangan
{
    public static function getAllUsers()
    {
        $connection = getConnection();
        $query = "SELECT keuangan.*, warga.nama
                  FROM keuangan
                  JOIN qurban ON keuangan.id_qurban = qurban.id_qurban
                  JOIN warga ON qurban.id_warga = warga.id_warga";
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

    public static function getKeuanganById($id)
    {
        $connection = getConnection();
        $query = "SELECT * FROM keuangan WHERE id_keuangan = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public static function createKeuangan($nama, $jenis_transaksi, $jumlah, $deskripsi, $tanggal_transaksi)
    {
        $connection = getConnection();
        // Cari id_qurban berdasarkan nama
        $queryQurban = "SELECT q.id_qurban FROM qurban q JOIN warga w ON q.id_warga = w.id_warga WHERE w.nama = ?";
        $stmtQurban = $connection->prepare($queryQurban);
        $stmtQurban->bind_param('s', $nama);
        $stmtQurban->execute();
        $resultQurban = $stmtQurban->get_result();
        $rowQurban = $resultQurban->fetch_assoc();
        $id_qurban = $rowQurban['id_qurban'] ?? null;

        if (!$id_qurban) {
            return false;
        }

        $query = "INSERT INTO keuangan (id_qurban, jenis_transaksi, jumlah, deskripsi, tanggal_transaksi) VALUES (?, ?, ?, ?, ?)";
        $stmt = $connection->prepare($query);
        $stmt->bind_param('isdss', $id_qurban, $jenis_transaksi, $jumlah, $deskripsi, $tanggal_transaksi);
        return $stmt->execute();
    }

    public static function updateKeuangan($id, $nama, $jenis_transaksi, $jumlah, $deskripsi, $tanggal_transaksi)
    {
        $connection = getConnection();
        // Cari id_qurban berdasarkan nama
        $queryQurban = "SELECT q.id_qurban FROM qurban q JOIN warga w ON q.id_warga = w.id_warga WHERE w.nama = ?";
        $stmtQurban = $connection->prepare($queryQurban);
        $stmtQurban->bind_param('s', $nama);
        $stmtQurban->execute();
        $resultQurban = $stmtQurban->get_result();
        $rowQurban = $resultQurban->fetch_assoc();
        $id_qurban = $rowQurban['id_qurban'] ?? null;

        if (!$id_qurban) {
            return false;
        }

        $query = "UPDATE keuangan SET id_qurban = ?, jenis_transaksi = ?, jumlah = ?, deskripsi = ?, tanggal_transaksi = ? WHERE id_keuangan = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param('isdssi', $id_qurban, $jenis_transaksi, $jumlah, $deskripsi, $tanggal_transaksi, $id);
        return $stmt->execute();
    }

    public static function deleteKeuangan($id)
    {
        $connection = getConnection();
        $query = "DELETE FROM keuangan WHERE id_keuangan = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}