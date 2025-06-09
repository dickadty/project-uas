<?php
session_start();
require_once 'config/db.php';

$db = getConnection();

if (isset($_GET['qr'])) {
    // get qr code using this weird new api
    $qrcode = file_get_contents("https://api.qrserver.com/v1/create-qr-code/?data=" . $_GET['qr']);
    $null = NULL;

    $stmt = $db->prepare("UPDATE pembagian_qurban SET qrcode=? WHERE id_warga=?");
    $stmt->bind_param("bs", $null, $_SESSION['id_warga']);
    $stmt->send_long_data(0, $qrcode);
    $stmt->execute();

    header("Location: qrcode");
} else {
    $stmt = $db->prepare("SELECT * FROM pembagian_qurban WHERE id_warga=?");
    $stmt->bind_param("s", $_SESSION['id_warga']);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = $result->fetch_assoc();
}


$basePath = 'http://' . $_SERVER['HTTP_HOST'] . '/project-uas';
ob_start();
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
                        <?php if (!empty($data['qrcode'])): ?>
                            <div class="text-center">
                                <h4>QR Code untuk Pembagian Qurban</h4>
                                <p><?= $data['qr_token'] ?></p>
                                <img src='data:image;base64,<?= base64_encode($data["qrcode"]) ?>' alt="QR Code" width="300">
                                <br><br>
                                <a href="<?= htmlspecialchars($data['qrcode']) ?>" download class="btn btn-primary">Unduh QR Code</a>
                            </div>
                        <?php else: ?>
                            <div class="text-center">
                                <p><strong>QR Code belum tersedia. Silakan buat QR Code terlebih dahulu.</strong></p>
                                <a href="?qr=<?= htmlspecialchars($data['qr_token'])?>" class="btn btn-primary">Buat QR Code</a>
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
