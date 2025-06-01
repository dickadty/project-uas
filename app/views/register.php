<?php
if (isset($_POST['nik'])) {
    require_once __DIR__ . '/../../config/db.php';
    $db = getConnection();

    $nama       = $_POST['nama'];
    $nik        = $_POST['nik'];
    $password   = $_POST['password'];
    $no_hp      = $_POST['no_hp'];
    $no_rumah   = $_POST['no_rumah'];
    $role       = $_POST['role'];       
    $nama_hewan = $_POST['nama_hewan'] ?? null;


    $stmt = $db->prepare(
        "INSERT INTO users (nik, password, role) VALUES (?, ?, ?)"
    );
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $stmt->bind_param("sss", $nik, $hashed_password, $role);
    $stmt->execute();
    $stmt->close();

    $is_panitia = ($role === 'panitia')   ? 1 : 0;
    $is_qurban  = ($role === 'berqurban') ? 1 : 0;
    $is_admin   = ($role === 'admin')     ? 1 : 0;

    $stmt = $db->prepare(
        "INSERT INTO warga (nik, nama, no_hp, no_rumah,
                            is_panitia, is_qurban, is_admin)
         VALUES (?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param(
        "ssssiis",
        $nik,
        $nama,
        $no_hp,
        $no_rumah,
        $is_panitia,
        $is_qurban,
        $is_admin
    );
    $stmt->execute();
    $id_warga = $stmt->insert_id;
    $stmt->close();

    if ($role === 'berqurban' && $nama_hewan !== null) {
        $stmt = $db->prepare(
            "SELECT id_hewan FROM hewan WHERE nama_hewan = ? LIMIT 1"
        );
        $stmt->bind_param("s", $nama_hewan);
        $stmt->execute();
        $stmt->bind_result($id_hewan);
        $found = $stmt->fetch();
        $stmt->close();

        if ($found) {
            $status_pembayaran = 'belum selesai';

            $stmt = $db->prepare(
                "INSERT INTO qurban (id_hewan, id_warga, status_pembayaran)
                 VALUES (?, ?, ?)"
            );
            $stmt->bind_param(
                "iis",
                $id_hewan,
                $id_warga,
                $status_pembayaran
            );
            $stmt->execute();
            $stmt->close();
        }
    }

    header("Location: login");
    exit;
}
?>


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
                    <select class="form-control" name="nama_hewan" id="nama_hewan">
                        <option value="" disabled selected hidden>Pilih Hewan Qurban </option>
                        <option value="kambing">Kambing Rp 2.750.000 </option>
                        <option value="sapi">Sapi Rp 3.010.000 </option>
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
                    $('#hewan_hewan').attr('required', true);
                } else {
                    $('#hewan-qurban-group').hide();
                    $('#hewan_hewan').removeAttr('required');
                }
            });
        });
    </script>
</body>

</html>