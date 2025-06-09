<?php
require_once 'app/controllers/PembagianQurbanController.php';

session_start();
require_once 'config/db.php';

$db = getConnection();

// Handle QR token POST for update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['token'])) {
    $qr_token = $_POST['token'];
    $update = $db->prepare("UPDATE pembagian_qurban SET status_ambil = 1 WHERE qr_token = ?");
    $update->bind_param('s', $qr_token);
    $update->execute();
    $ambil_success = $update->affected_rows > 0;
    $update->close();
}

// Fetch all pembagian_qurban joined with warga to get names
$stmt = $db->prepare("
    SELECT pq.*, w.*
    FROM pembagian_qurban pq
    JOIN warga w ON pq.id_warga = w.id_warga
");
$stmt->execute();
$result = $stmt->get_result();

$dataList = [];
while ($row = $result->fetch_assoc()) {
    $dataList[] = $row;
}

use App\Controllers\PembagianQurbanController;

$pembagian = new PembagianQurbanController();

ob_start();
?>

<div class="row">
    <div class="col-lg-7" style="display: flex; flex-direction: column;">
        <div class="ibox float-e-margins" style="flex: 1 1 auto;">
            <div class="ibox-title d-flex justify-content-between align-items-center">
                <h5>Status Pengambilan Daging</h5>
                <!-- <a href="#" class="btn btn-sm btn-dark">Atur Ulang Pembagian</a> -->
            </div>
            <div class="ibox-content">
                <style>
                    .card-status {
                        background: #fff;
                        border-radius: 14px;
                        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
                        transition: box-shadow 0.2s;
                        padding: 1.5rem 1.5rem !important;
                        border: 2px solid #e0e0e0;
                    }
                    .card-status:not(:last-child) {
                        margin-bottom: 24px !important;
                    }
                    .card-status:hover {
                        box-shadow: 0 4px 18px rgba(0,0,0,0.12);
                        border-color: #bdbdbd;
                    }
                    .card-list-container {
                        max-height: calc(80vh);
                        overflow-y: auto;
                        padding-right: 8px;
                    }
                </style>
                <div class="card-list-container">
                    <?php if ($dataList): ?>
                        <?php foreach ($dataList as $data): ?>
                            <div class="card-status d-flex justify-content-between align-items-center border rounded p-4 mb-4">
                                <div>
                                    <h5 class="fw-bold mb-1"><?= htmlspecialchars($data['nama']) ?></h5>
                                    <p class="text-muted mb-0">
                                        <?= htmlspecialchars($data['jumlah_kg']) ?> Kg - Kategori: 
                                        <?php
                                            // Determine role from flags
                                            if (!empty($data['is_admin'])) {
                                                $kategori = 'Admin';
                                            } elseif (!empty($data['is_panitia'])) {
                                                $kategori = 'Panitia';
                                            } elseif (!empty($data['is_qurban'])) {
                                                $kategori = 'Qurban';
                                            } elseif (!empty($data['is_warga'])) {
                                                $kategori = 'Warga';
                                            } else {
                                                $kategori = 'Warga';
                                            }
                                            echo htmlspecialchars($kategori);
                                        ?>
                                    </p>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <?php
                                        $status = ($data['status_ambil'] ?? 0) == 1 ? 'Sudah Diambil' : 'Belum Diambil';
                                        // Use badge-success for done, badge-warning for not yet
                                        $badgeClass = ($status === 'Sudah Diambil')
                                            ? 'badge badge-success rounded-pill px-3 py-2'
                                            : 'badge badge-warning rounded-pill px-3 py-2';
                                    ?>
                                    <span class="<?= $badgeClass ?>"><?= $status ?></span>
                                    <!-- <a href="#" class="btn btn-outline-dark btn-sm">Ubah Status</a> -->
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-warning">Data pembagian qurban tidak ditemukan.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5" style="display: flex; flex-direction: column;">
        <div class="ibox float-e-margins" style="flex: 1 1 auto;">
            <div class="ibox-title">
                <h5>Scan QR Code</h5>
            </div>
            <div class="ibox-content d-flex justify-content-center align-items-center" style="height:100%; min-height:350px;">
                <style>
                    .qr-drop-area {
                        border: 2px dashed #bbb;
                        border-radius: 12px;
                        padding: 32px 16px;
                        background: #fafafa;
                        color: #888;
                        text-align: center;
                        transition: border-color 0.2s, background 0.2s;
                        cursor: pointer;
                        width: 100%;
                        height: 220px;
                        display: flex;
                        flex-direction: column;
                        justify-content: center;
                        align-items: center;
                    }
                    .qr-drop-area.dragover {
                        border-color: #007bff;
                        background: #e3f0ff;
                        color: #007bff;
                    }
                    .qr-upload-btn {
                        margin-top: 18px;
                        display: block;
                        margin-left: auto;
                        margin-right: auto;
                    }
                    .qr-upload-btn-wrapper {
                        width: 100%;
                        display: flex;
                        justify-content: center;
                    }
                </style>
                <div style="width:100%;">
                    <div id="qrDropArea" class="qr-drop-area">
                        <i class="fa fa-qrcode" style="font-size:3em;"></i>
                        <p style="margin:12px 0 0 0;">Seret dan lepas gambar QR code di sini</p>
                        <p style="font-size:0.95em;color:#aaa;">atau pilih file secara manual</p>
                    </div>
                    <input type="file" id="qrFileInput" accept="image/*" style="display:none;">
                    <div class="qr-upload-btn-wrapper">
                        <button type="button" class="btn btn-primary qr-upload-btn" onclick="document.getElementById('qrFileInput').click();">
                            <i class="fa fa-upload"></i> Upload Gambar QR Code
                        </button>
                    </div>
                    <div id="qrImagePreview" style="margin-top:18px; text-align:center;"></div>
                    <div id="qrScanResult" style="margin-top:18px; color:#333;"></div>
                </div>
                <script src="https://unpkg.com/jsqr/dist/jsQR.js"></script>
                <script>
                    const dropArea = document.getElementById('qrDropArea');
                    const fileInput = document.getElementById('qrFileInput');
                    const resultDiv = document.getElementById('qrScanResult');
                    const imagePreviewDiv = document.getElementById('qrImagePreview');

                    function handleFiles(files) {
                        if (!files.length) return;
                        const file = files[0];
                        if (!file.type.startsWith('image/')) {
                            resultDiv.textContent = "File bukan gambar.";
                            imagePreviewDiv.innerHTML = "";
                            return;
                        }
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const img = new Image();
                            img.onload = function() {
                                // Show the image below the drop box
                                imagePreviewDiv.innerHTML = '';
                                img.style.maxWidth = '260px';
                                img.style.maxHeight = '180px';
                                img.style.borderRadius = '8px';
                                img.style.boxShadow = '0 2px 8px rgba(0,0,0,0.08)';
                                imagePreviewDiv.appendChild(img);

                                // Create canvas to draw image
                                const canvas = document.createElement('canvas');
                                canvas.width = img.width;
                                canvas.height = img.height;
                                const ctx = canvas.getContext('2d');
                                ctx.drawImage(img, 0, 0, img.width, img.height);
                                const imageData = ctx.getImageData(0, 0, img.width, img.height);
                                const code = jsQR(imageData.data, img.width, img.height);
                                if (code) {
                                    resultDiv.innerHTML = "<b>QR Code ditemukan:</b><br><span style='word-break:break-all; color:#888; font-size:0.95em;'>" + code.data + "</span>";
                                    // Fetch data from PHP using AJAX
                                    fetch('qr/read?token=' + encodeURIComponent(code.data))
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data && Object.keys(data).length > 0) {
                                                if (data.status_ambil == 1) {
                                                    resultDiv.innerHTML += `
                                                        <hr>
                                                        <div class="alert alert-success text-center" style="font-size:1.15em;">
                                                            <i class="fa fa-check-circle"></i> Sudah diambil
                                                        </div>
                                                    `;
                                                } else {
                                                    // Determine role
                                                    let role = "Warga";
                                                    if (data.is_admin == 1) role = "Administrator";
                                                    else if (data.is_panitia == 1) role = "Panitia";
                                                    else if (data.is_qurban == 1) role = "Berqurban";
                                                    else if (data.is_warga == 1) role = "Warga";

                                                    // Format and show the information
                                                    let html = `
                                                        <hr>
                                                        <div class="card" style="max-width:350px;margin:0 auto;">
                                                            <div class="card-body">
                                                                <h5 class="card-title mb-2">${data.nama ? data.nama : '-'}</h5>
                                                                <table class="table table-borderless mb-2" style="width:100%;">
                                                                    <tr>
                                                                        <th style="width:110px;">NIK</th>
                                                                        <td>${data.nik ? data.nik : '-'}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Role</th>
                                                                        <td>${role}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Jatah Daging</th>
                                                                        <td>${data.jumlah_kg ? data.jumlah_kg + ' Kg' : '-'}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Alamat</th>
                                                                        <td>${data.no_rumah ? data.no_rumah : '-'}</td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    `;
                                                    resultDiv.innerHTML += html;
                                                    // Add the button under the info card
                                                    resultDiv.innerHTML += createAmbilButton(code.data);
                                                }
                                            } else {
                                                resultDiv.innerHTML += "<hr><span style='color:red;'>Data tidak ditemukan untuk token QR ini.</span>";
                                            }
                                        })
                                        .catch(err => {
                                            resultDiv.innerHTML += "<hr><span style='color:red;'>Gagal mengambil data dari server.</span>";
                                        });
                                } else {
                                    resultDiv.textContent = "QR Code tidak ditemukan di gambar.";
                                }
                            };
                            img.onerror = function() {
                                resultDiv.textContent = "Gagal memuat gambar.";
                                imagePreviewDiv.innerHTML = "";
                            };
                            img.src = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }

                    dropArea.addEventListener('dragover', function(e) {
                        e.preventDefault();
                        dropArea.classList.add('dragover');
                    });
                    dropArea.addEventListener('dragleave', function(e) {
                        dropArea.classList.remove('dragover');
                    });
                    dropArea.addEventListener('drop', function(e) {
                        e.preventDefault();
                        dropArea.classList.remove('dragover');
                        handleFiles(e.dataTransfer.files);
                    });
                    dropArea.addEventListener('click', function() {
                        fileInput.click();
                    });
                    fileInput.addEventListener('change', function(e) {
                        handleFiles(e.target.files);
                    });

                    function createAmbilButton(token) {
                        return `
                            <form method="POST" action="" style="margin-top:18px;text-align:center;">
                                <input type="hidden" name="token" value="${token}">
                                <button type="submit" class="btn btn-primary">
                                    Tandai Sudah Diambil
                                </button>
                            </form>
                        `;
                    }
                </script>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
include_once __DIR__ . '/../templates/layout.php';
?>
<?php if (isset($ambil_success)): ?>
    <div id="ambilNotif"
        class="alert alert-<?= $ambil_success ? 'success' : 'danger' ?> alert-dismissable"
        style="position:fixed;top:30px;right:30px;z-index:9999;min-width:260px;max-width:350px;">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <?= $ambil_success ? "Status pengambilan berhasil diupdate!" : "Gagal update status pengambilan!" ?>
    </div>
    <script>
        setTimeout(function() {
            var notif = document.getElementById('ambilNotif');
            if (notif) notif.style.display = 'none';
        }, 3500);
    </script>
<?php endif; ?>
