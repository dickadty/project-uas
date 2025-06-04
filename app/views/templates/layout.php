<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once __DIR__ . '/../../../includes/header.php'; ?> <!-- Memasukkan header -->
</head>

<body>

    <div id="wrapper">
        <!-- Sidebar -->
        <?php include_once __DIR__ . '/../../../includes/sidebar_admin.php'; ?> <!-- Memasukkan sidebar -->

        <div id="page-wrapper" class="gray-bg">
            <!-- Page Content -->
            <div class="row border-bottom">
                <div class="col-lg-10">
                    <!-- Judul Halaman, bisa diganti sesuai dengan konten yang dimasukkan -->
                    <h2><?= isset($judulHalaman) ? $judulHalaman : 'Dashboard Admin' ?></h2>


                </div>
            </div>

            <!-- Konten Halaman -->
            <div class="content">
                <?= isset($content) ? $content : 'Konten utama halaman akan muncul di sini.' ?>
            </div>
        </div>
        <?php include_once __DIR__ . '/../../../includes/footer.php'; ?> <!-- Memasukkan footer -->
    </div>
</body>

</html>