<!DOCTYPE html>
<html lang="en">
<?php include '../config/head.php'; ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Admin Dashboard</title>
    <style>
        /* Mengatur layout wrapper agar menggunakan flexbox */
        #wrapper {
            display: flex;
            height: 100vh; /* Pastikan wrapper mengisi seluruh tinggi layar */
            flex-direction: row; /* Sidebar di kiri, konten di kanan */
        }

        /* Sidebar */
        .navbar-default {
            width: 250px; /* Lebar sidebar */
            position: fixed; /* Sidebar tetap berada di kiri */
            top: 0;
            bottom: 0; /* Sidebar memenuhi seluruh tinggi halaman */
            left: 0;
            z-index: 1000; /* Sidebar tetap di atas konten */
            background-color: #2c3e50; /* Ganti warna sesuai desain */
        }

        /* Konten utama */
        #page-wrapper {
            margin-left: 250px; /* Memberikan ruang untuk sidebar */
            padding: 20px;
            flex-grow: 1; /* Konten utama mengisi ruang yang tersisa */
            overflow-y: auto; /* Membuat konten scrollable jika terlalu tinggi */
            background-color: #ecf0f1; /* Ganti warna sesuai desain */
        }

        /* Responsif untuk tampilan kecil */
        @media (max-width: 768px) {
            #wrapper {
                flex-direction: column; /* Untuk tampilan kecil, sidebar berada di bawah header */
            }

            .navbar-default {
                width: 100%; /* Sidebar menjadi penuh pada perangkat kecil */
                position: relative;
            }

            #page-wrapper {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>
    <!-- Include Header -->
    <?php include '../includes/header.php'; ?>

    <!-- Wrapper untuk Flex Layout -->
    <div id="wrapper" class="d-flex">
        <!-- Include Sidebar -->
        <?php include '../includes/sidebar.php'; ?>

        <!-- Main Content -->
        <div id="page-wrapper">
            <div class="content">
                <!-- Konten halaman utama -->
            </div>
        </div>
    </div>

    <!-- Include Footer -->
    <?php include '../includes/footer.php'; ?>

    <!-- Include Scripts -->
    <?php include '../config/scripts.php'; ?>
</body>

</html>
