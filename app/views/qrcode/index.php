<?php
// Pastikan data QR Code sudah diteruskan dari controller
$data = $data ?? []; // Pastikan data tersedia
$basePath = 'http://' . $_SERVER['HTTP_HOST'] . '/project-uas';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Generator</title>
    <?php include_once __DIR__ . '/../../../includes/header.php'; ?> <!-- Memasukkan header -->
</head>

<body>
    <div id="wrapper">
        <!-- Sidebar -->
        <?php include_once __DIR__ . '/../../../includes/sidebar_admin.php'; ?> <!-- Memasukkan sidebar -->

        <div id="page-wrapper" class="gray-bg">
            <!-- Header dan Judul -->
            <div class="row border-bottom">
                <div class="col-lg-10">
                    <h2>QR Code Generator</h2>
                </div>
            </div>

            <!-- Konten QR Code -->
            <div class="content">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>QR Code Pembagian Qurban</h5>
                    </div>
                    <div class="ibox-content">
                        <?php if (!empty($data['qrCodePath'])): ?>
                            <div class="text-center">
                                <h4>QR Code untuk Pembagian Qurban</h4>
                                <img src="<?= htmlspecialchars($data['qrCodePath']) ?>" alt="QR Code" width="300">
                                <br><br>
                                <a href="<?= htmlspecialchars($data['qrCodePath']) ?>" download class="btn btn-primary">Unduh QR Code</a>
                            </div>
                        <?php else: ?>
                            <div class="text-center">
                                <p><strong>QR Code belum tersedia. Silakan buat QR Code terlebih dahulu.</strong></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <?php include_once __DIR__ . '/../../includes/footer.php'; ?> <!-- Memasukkan footer -->
    </div>
</body>

</html>
