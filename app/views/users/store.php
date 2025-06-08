<?php
require_once __DIR__ . '/../../../config/db.php';
$db = getConnection();

if (isset($_POST['nik'])) {

    $nama       = $_POST['nama'];
    $nik        = $_POST['nik'];
    $password   = $_POST['password'];
    $no_hp      = $_POST['no_hp'];
    $no_rumah   = $_POST['no_rumah'];
    $role       = $_POST['role'];

    // Hash password untuk keamanan
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert ke tabel users
    $stmt = $db->prepare("INSERT INTO users (nik, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nik, $hashed_password, $role);
    $stmt->execute();
    $stmt->close();

    // Tentukan role untuk tabel warga
    $is_panitia = ($role === 'panitia') ? 1 : 0;
    $is_qurban  = ($role === 'berqurban') ? 1 : 0;
    $is_admin   = ($role === 'admin') ? 1 : 0;
    $is_warga   = ($role === 'warga') ? 1 : 0; // Menambahkan is_warga

    // Insert ke tabel warga
    $stmt = $db->prepare(
        "INSERT INTO warga (nik, nama, no_hp, no_rumah, is_panitia, is_qurban, is_admin, is_warga)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("ssssiiii", $nik, $nama, $no_hp, $no_rumah, $is_panitia, $is_qurban, $is_admin, $is_warga);
    $stmt->execute();
    $id_warga = $stmt->insert_id;  // Ambil ID warga yang baru dimasukkan
    $stmt->close();

    header('Location: ../dashboard');
    exit;
}
?>

