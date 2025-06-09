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
    <title>Ambil QR Code</title>
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
                        <style>
                            .qrcode-card {
                                max-width: 400px;
                                margin: 0 auto;
                                background: #fff;
                                border-radius: 16px;
                                box-shadow: 0 4px 24px rgba(0,0,0,0.08);
                                padding: 32px 24px 24px 24px;
                            }
                            .qrcode-img {
                                border-radius: 12px;
                                border: 2px solid #eee;
                                background: #fafafa;
                                padding: 12px;
                                margin-bottom: 16px;
                            }
                            .qr-token {
                                font-size: 1.1em;
                                color: #555;
                                margin-bottom: 12px;
                                word-break: break-all;
                            }
                            .btn-download {
                                margin-top: 12px;
                            }
                        </style>
                        <?php if (!empty($data['qrcode'])): ?>
                            <?php
                                $status = ($data['status_ambil'] ?? 0) == 1 ? 'Sudah diambil' : 'Belum Diambil';
                                $badgeClass = ($status === 'Sudah diambil')
                                    ? 'badge badge-success'
                                    : 'badge badge-warning';
                                $cardStyle = ($status === 'Sudah diambil')
                                    ? 'opacity:0.6; filter: grayscale(1); pointer-events: none;'
                                    : '';
                            ?>
                            <div class="qrcode-card text-center" style="<?= $cardStyle ?>" id="kartu-qurban">
                                <h4 class="mb-3" style="font-weight:600;">Kartu Pembagian Qurban</h4>
                                <div class="mb-2">
                                    <span style="font-size:2em"><strong><?= htmlspecialchars($_SESSION['nama']) ?></strong></span>
                                    <br>
                                    <span style="font-size:1em; color:#888;">
                                        NIK: <?= htmlspecialchars($_SESSION['nik']) ?>
                                    </span>
                                </div>
                                <div style="height:18px;"></div>
                                <img class="qrcode-img" src='data:image;base64,<?= base64_encode($data["qrcode"]) ?>' alt="QR Code" width="220" crossorigin="anonymous">
                                <div class="mb-2" style="font-size:1.05em; color:#2d7a2d;">
                                    <strong>Jatah Daging:</strong> <?= htmlspecialchars($data["jumlah_kg"]) ?> kg
                                </div>
                                <div style="font-size:0.95em; color:#555; margin-top:18px;">
                                    <em>Tunjukkan kartu ini ke panitia ketika mengambil daging qurban.</em>
                                </div>
                                <div style="margin-top:14px;">
                                    <span class="<?= $badgeClass ?>" style="font-size:1em;">
                                        <?= $status ?>
                                    </span>
                                </div>
                            </div>
                            <div class="text-center">
                                <?php if ($status === 'Sudah diambil'): ?>
                                    <a href="#" class="btn btn-secondary btn-download mt-3" style="pointer-events:none;opacity:0.7;">
                                        <i class="fa fa-check"></i> Sudah Dipakai
                                    </a>
                                <?php else: ?>
                                    <button type="button" class="btn btn-primary btn-download mt-3" onclick="downloadKartu()">
                                        <i class="fa fa-download"></i> Unduh QR Code
                                    </button>
                                <?php endif; ?>
                            </div>
                            <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
                            <script>
                            function downloadKartu() {
                                var card = document.getElementById('kartu-qurban');
                                html2canvas(card, {
                                    useCORS: true,
                                    scale: 3
                                }).then(function(canvas) {
                                    var link = document.createElement('a');
                                    link.download = 'kartu-qurban.png';
                                    link.href = canvas.toDataURL();
                                    link.click();
                                });
                            }
                            </script>
                        <?php else: ?>
                            <div class="qrcode-card text-center">
                                <p><strong>QR Code belum tersedia. Silakan buat QR Code terlebih dahulu.</strong></p>
                                <a href="?qr=<?= htmlspecialchars($data['qr_token'])?>" class="btn btn-success">
                                    <i class="fa fa-qrcode"></i> Buat QR Code
                                </a>
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
