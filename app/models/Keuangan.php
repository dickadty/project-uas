<?php

namespace App\Models;

require_once __DIR__ . '/../../config/db.php';

class Keuangan
{
    public static function getAllKeuangan()
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
        try {
            $connection = getConnection();
            // Assuming `warga` has a `nama` column, we need the id_qurban first
            $queryQurban = "SELECT q.id_qurban FROM warga w JOIN qurban q ON q.id_warga = w.id_warga WHERE w.nama = ? AND is_panitia = 1";
            $stmtQurban = $connection->prepare($queryQurban);
            if (!$stmtQurban) {
                throw new \Exception("Prepare failed: " . $connection->error);
            }
            $stmtQurban->bind_param('s', $nama);
            if (!$stmtQurban->execute()) {
                throw new \Exception("Execute failed: " . $stmtQurban->error);
            }
            $resultQurban = $stmtQurban->get_result();
            $rowQurban = $resultQurban->fetch_assoc();
            $id_qurban = $rowQurban['id_qurban'] ?? null;

            if (!$id_qurban) {
                throw new \Exception("id_qurban not found for nama: " . $nama);
            }

            $query = "INSERT INTO keuangan (id_qurban, jenis_transaksi, jumlah, deskripsi, tanggal_transaksi) VALUES (?, ?, ?, ?, ?)";
            $stmt = $connection->prepare($query);
            if (!$stmt) {
                throw new \Exception("Prepare failed: " . $connection->error);
            }
            $stmt->bind_param('isdss', $id_qurban, $jenis_transaksi, $jumlah, $deskripsi, $tanggal_transaksi);
            if (!$stmt->execute()) {
                throw new \Exception("Execute failed: " . $stmt->error);
            }
            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public static function updateKeuangan($id, $jenis_transaksi, $jumlah, $deskripsi, $tanggal_transaksi)
    {
        $connection = getConnection();
        // Fetch `id_qurban` based on the given role or transaction
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
