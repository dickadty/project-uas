<?php
require_once __DIR__ . '/../../config/db.php';
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
            $id_qurban = $stmt->insert_id;  // Ambil id_qurban yang baru dimasukkan
            $stmt->close();
        }
    }

    // Jika id_qurban adalah null dan role = warga, set id_qurban menjadi NULL
    if ($id_qurban === null && $role === 'warga') {
        $id_qurban = NULL;
    }

    // Tentukan jumlah_kg berdasarkan role
    $jumlah_kg = 2;
    if ($role === 'berqurban') {
        $jumlah_kg = 6;
    }

    $status_ambil = 0;  // Status ambil diatur ke 0 (belum diambil)

    // Membuat QR token secara otomatis
    $qr_token = uniqid('qr_', true);

    // Insert data ke tabel pembagian_qurban
    $stmt = $db->prepare(
        "INSERT INTO pembagian_qurban (id_warga, id_qurban, jumlah_kg, status_ambil, qr_token)
         VALUES (?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("iiiss", $id_warga, $id_qurban, $jumlah_kg, $status_ambil, $qr_token);
    $stmt->execute();
    $stmt->close();

    // Redirect ke halaman login setelah registrasi dan insert selesai
    header("Location: login");
    exit;
}
?>

<?php
$basePath = 'http://' . $_SERVER['HTTP_HOST'] . '/project-uas';
include_once __DIR__ . '/../../../includes/header.php'; ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Akun | Qurban</title>
    <?php include_once __DIR__ . '/../../includes/header.php'; ?>
</head>

<body class="gray-bg">

    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <div>
                <h1 class="logo-name">REG</h1>
            </div>
            <h3>Daftar</h3>
            <p>Buat akun untuk berqurban/menerima qurban.</p>
            <form class="m-t" role="form" action="" method="post">
                <p class="text-muted text-left"><small>Kredensial</small></p>
                <div class="form-group">
                    <input type="text" name="nama" class="form-control" placeholder="Nama" required>
                </div>
                <div class="form-group">
                    <input type="text" name="nik" class="form-control" placeholder="NIK" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <p class="text-muted text-left"><small>Data diri</small></p>
                <div class="form-group">
                    <input type="text" name="no_hp" class="form-control" placeholder="No. HP" required>
                </div>
                <div class="form-group">
                    <input type="text" name="no_rumah" class="form-control" placeholder="No. Rumah, e.g. A4 504" required>
                </div>
                <div class="form-group">
                    <select class="form-control" name="role" id="role" required>
                        <option value="" disabled selected hidden>Daftar untuk bagian...</option>
                        <option value="warga">Warga</option>
                        <option value="berqurban">Berqurban</option>
                        <option value="panitia">Panitia</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div class="form-group" id="hewan-qurban-group" style="display: none;">
                    <select class="form-control" name="jenis_hewan" id="jenis_hewan">
                        <option value="" disabled selected hidden>Pilih Hewan Qurban</option>
                        <option value="kambing">Kambing Rp 2.750.000</option>
                        <option value="sapi">Sapi Rp 3.020.000</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary block full-width m-b">Register</button>

                <p class="text-muted text-center"><small>Sudah punya akun?</small></p>
                <a class="btn btn-sm btn-white btn-block" href="login">Login</a>
            </form>
        </div>
    </div>

    <?php include_once __DIR__ . '/../../includes/footer.php'; ?>
    <script src="js/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#role').change(function() {
                if ($(this).val() === 'berqurban') {
                    $('#hewan-qurban-group').show();
                    $('#jenis_hewan').attr('required', true);
                } else {
                    $('#hewan-qurban-group').hide();
                    $('#jenis_hewan').removeAttr('required');
                }
            });
        });
    </script>
</body>

</html>