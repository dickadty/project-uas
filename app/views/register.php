<?php

if (isset($_POST['nik'])) {
    include_once __DIR__ . '/../../config/db.php';
    $db = getConnection();

    $nama = $_POST['nama'];
    $nik = $_POST['nik'];
    $password = $_POST['password'];
    $no_hp = $_POST['no_hp'];
    $no_rumah = $_POST['no_rumah'];
    $role = $_POST['role'];

    $stmt = $db->prepare("INSERT INTO `users`(`nik`, `password`, `role`) VALUES (?,?,?)");
    $stmt->bind_param("sss", $nik, password_hash($password, PASSWORD_BCRYPT), $role);
    $stmt->execute();

    $is_panitia = ($role === "panitia") ? 1 : 0;
    $is_qurban = ($role === "berqurban") ? 1 : 0;

    $stmt = $db->prepare("INSERT INTO `warga`(`nik`, `nama`, `no_hp`, `no_rumah`, `is_panitia`, `is_qurban`) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param("ssssss", $nik, $nama, $no_hp, $no_rumah, $is_panitia, $is_qurban);
    $stmt->execute();

    header("Location: login");
}

?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>INSPINIA | Register</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <?php include_once __DIR__ . '/../../includes/header.php'; ?>
</head>

<body class="gray-bg">

    <div class="middle-box text-center loginscreen   animated fadeInDown">
        <div>
            <div>

                <h1 class="logo-name">REG</h1>

            </div>
            <h3>Daftar</h3>
            <p>Buat akun untuk berqurban/menerima qurban.</p>
            <form class="m-t" role="form" action="" method="post">
                <p class="text-muted text-left"><small>Kredensial</small></p>
                <div class="form-group">
                    <input type="text" name="nama" class="form-control" placeholder="Nama" required="">
                </div>
                <div class="form-group">
                    <input type="text" name="nik" class="form-control" placeholder="NIK" required="">
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Password" required="">
                </div>
                <p class="text-muted text-left"><small>Data diri</small></p>
                <div class="form-group">
                    <input type="text" name="no_hp" class="form-control" placeholder="No. HP" required="">
                </div>
                <div class="form-group">
                    <input type="text" name="no_rumah" class="form-control" placeholder="No. Rumah, e.g. A4 504" required="">
                </div>
                <div class="form-group">
                    <select class="form-control" name="role" id="role" required>
                        <option value="" disabled selected hidden>Daftar untuk bagian...</option>
                        <option value="warga">Warga</option>
                        <option value="berqurban">Warga Berqurban</option>
                        <option value="panitia">Panitia</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b">Register</button>

                <p class="text-muted text-center"><small>Sudah punya akun?</small></p>
                <a class="btn btn-sm btn-white btn-block" href="login">Login</a>
            </form>
        </div>  
    </div>

    <!-- Mainly scripts -->
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!-- iCheck -->
    <script src="js/plugins/iCheck/icheck.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
        });
    </script>
</body>

</html>
