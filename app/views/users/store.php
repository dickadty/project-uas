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
    $jenis_hewan = $_POST['jenis_hewan'] ?? null;

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
    $is_warga   = ($role === 'warga') ? 1 : 0;

    // Insert ke tabel warga
    $stmt = $db->prepare(
        "INSERT INTO warga (nik, nama, no_hp, no_rumah, is_panitia, is_qurban, is_admin, is_warga)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("ssssiiii", $nik, $nama, $no_hp, $no_rumah, $is_panitia, $is_qurban, $is_admin, $is_warga);
    $stmt->execute();
    $id_warga = $stmt->insert_id;
    $stmt->close();

    $id_qurban = null;
    if ($role === 'berqurban' && $jenis_hewan !== null) {
        // Ambil id_hewan dari tabel hewan berdasarkan jenis_hewan
        $stmt = $db->prepare("SELECT id_hewan FROM hewan WHERE jenis_hewan = ? LIMIT 1");
        $stmt->bind_param("s", $jenis_hewan);
        $stmt->execute();
        $stmt->bind_result($id_hewan);
        $found = $stmt->fetch();
        $stmt->close();

        if ($found) {
            $status_pembayaran = 'belum selesai';

            // Insert data ke tabel qurban
            $stmt = $db->prepare(
                "INSERT INTO qurban (id_hewan, id_warga, status_pembayaran)
                 VALUES (?, ?, ?)"
            );
            $stmt->bind_param("iis", $id_hewan, $id_warga, $status_pembayaran);
            $stmt->execute();
            $id_qurban = $stmt->insert_id;
            $stmt->close();
        }
    }

    if ($id_qurban === null && $role === 'warga') {
        $id_qurban = NULL;
    }

    // Tentukan jumlah_kg berdasarkan role
    $jumlah_kg = 2;
    if ($role === 'berqurban') {
        $jumlah_kg = 6;
    }

    $status_ambil = 0;
    $qr_token = uniqid('qr_', true);

    // Insert data ke tabel pembagian_qurban
    $stmt = $db->prepare(
        "INSERT INTO pembagian_qurban (id_warga, id_qurban, jumlah_kg, status_ambil, qr_token)
         VALUES (?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("iiiss", $id_warga, $id_qurban, $jumlah_kg, $status_ambil, $qr_token);
    $stmt->execute();
    $stmt->close();

    header('Location: ../dashboard');
    exit;
}
?>

