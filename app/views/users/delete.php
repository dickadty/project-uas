<?php
require_once __DIR__ . '/../../../config/db.php';
$db = getConnection();

if (isset($_GET['id'])) {

    $id = $_GET['id'];

    // Insert ke tabel users
    $stmt = $db->prepare("SELECT nik FROM users WHERE id_users=?");
    $stmt->bind_param("s", $id);

    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_row();

    $stmt = $db->prepare("DELETE FROM warga WHERE nik=?");
    $stmt->bind_param("s", $data[0]);
    $stmt->execute();

    $stmt = $db->prepare("DELETE FROM users WHERE nik=?");
    $stmt->bind_param("s", $data[0]);
    $stmt->execute();


    header("Location: ../dashboard");
    exit;
}
?>

