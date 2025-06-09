<?php
require_once 'config/db.php';

$db = getConnection();

$qr_token = $_GET['token'] ?? '';

if ($qr_token) {
    $stmt = $db->prepare("
        SELECT pq.jumlah_kg, pq.status_ambil, w.*
        FROM pembagian_qurban pq
        JOIN warga w ON pq.id_warga = w.id_warga
        WHERE pq.qr_token = ?
        LIMIT 1
    ");
    $stmt->bind_param('s', $qr_token);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    // Return as JSON for use in AJAX or PHP include
    header('Content-Type: application/json');
    // print_r($data);
    echo json_encode($data);
    exit;
} else {
    header('Content-Type: application/json');
    echo json_encode([]);
    exit;
}