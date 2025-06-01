<?php
if (isset($_POST['nik'])) {
    include_once __DIR__ . '/../../config/db.php';
    $db = getConnection();

    $nik = $_POST['nik'];
    $password = $_POST['password'];

    $stmt = $db->prepare("SELECT password FROM `users` WHERE nik=?");
    $stmt->bind_param("s", $nik);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $data = $result->fetch_row();

            if (password_verify($password, $data[0])) {
                session_start();
                $stmt = $db->prepare("SELECT * FROM `warga` WHERE nik=?");
                $stmt->bind_param("s", $nik);
                $stmt->execute();
                $result = $stmt->get_result();
                $data = $result->fetch_assoc();
    
                $_SESSION['nama'] = $data['nama'];
                header("Location: dashboard");
                exit;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>INSPINIA | Login</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <?php include_once __DIR__ . '/../../includes/header.php'; ?> <!-- Memasukkan header -->

</head>

<body class="gray-bg">

    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <div>

                <h1 class="logo-name">IN</h1>

            </div>
            <h3>Welcome</h3>
            <p>Login untuk mengakses dashboard
                <!--Continually expanded and constantly improved Inspinia Admin Them (IN+)-->
            </p>
            <p>Login in. To see it in action.</p>
            <form class="m-t" role="form" action="" method="post">
                <div class="form-group">
                    <input type="text" class="form-control" name="nik" placeholder="NIK" required="">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="Password" required="">
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b">Login</button>

                <a href="#"><small>Forgot password?</small></a>
                <p class="text-muted text-center"><small>Do not have an account?</small></p>
                <a class="btn btn-sm btn-white btn-block" href="register">Create an account</a>
            </form>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <?php include_once __DIR__ . '/../../../includes/footer.php'; ?> <!-- Memasukkan footer -->
</body>

</html>
