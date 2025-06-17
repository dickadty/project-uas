<?php
require_once 'app/controllers/PembagianQurbanController.php';

session_start();
require_once 'config/db.php';

$db = getConnection();

// Handle QR token POST for update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['token'])) {
    $qr_token = $_POST['token'];
    $update = $db->prepare("UPDATE pembagian_qurban SET status_ambil = 1, waktu_ambil = CURRENT_TIMESTAMP WHERE qr_token = ?");
    $update->bind_param('s', $qr_token);
    $update->execute();
    $ambil_success = $update->affected_rows > 0;
    $update->close();
}

// Sorting logic
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'latest';
switch ($sort) {
    case 'name':
        $orderBy = 'w.nama ASC';
        break;
    case 'role':
        // Sort by is_admin > is_panitia > is_qurban > is_warga > nama
        $orderBy = "w.is_admin DESC, w.is_panitia DESC, w.is_qurban DESC, w.is_warga DESC, w.nama ASC";
        break;
    case 'status':
        // Sudah Diambil (1) first, then Belum Diambil (0), then waktu_ambil DESC
        $orderBy = "pq.status_ambil DESC, pq.waktu_ambil DESC";
        break;
    case 'latest':
    default:
        $orderBy = "pq.waktu_ambil DESC, pq.id_pembagian DESC";
        break;
}

// Fetch all pembagian_qurban joined with warga to get names, with sorting
$stmt = $db->prepare("
    SELECT pq.*, w.*
    FROM pembagian_qurban pq
    JOIN warga w ON pq.id_warga = w.id_warga
    ORDER BY $orderBy
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
            </div>
            <div class="mb-3 d-flex" style="padding-top:10px;">
                <div class="btn-group" id="sort-filter-group">
                    <button type="button" class="btn btn-sm btn-white<?= $sort === 'latest' ? ' active' : '' ?>" onclick="setSort('latest')">Terbaru Diambil</button>
                    <button type="button" class="btn btn-sm btn-white<?= $sort === 'name' ? ' active' : '' ?>" onclick="setSort('name')">Nama</button>
                    <button type="button" class="btn btn-sm btn-white<?= $sort === 'role' ? ' active' : '' ?>" onclick="setSort('role')">Role</button>
                    <button type="button" class="btn btn-sm btn-white<?= $sort === 'status' ? ' active' : '' ?>" onclick="setSort('status')">Status Pengambilan</button>
                </div>
                <div id="pengambilan-export-buttons"></div>
                <style>
                    #pengambilan-export-buttons .dt-button,
                    .dt-buttons .dt-button {
                        background: #f5f5f5;
                        color: #333;
                        border: 1px solid #e7eaec;
                        border-radius: 4px;
                        padding: 4px 12px;
                        margin-right: 6px;
                        font-size: 12px;
                        font-weight: 400;
                        box-shadow: none;
                        transition: background 0.2s, color 0.2s, border 0.2s;
                        outline: none;
                        height: 28px;
                        line-height: 20px;
                        display: inline-flex;
                        align-items: center;
                    }
                    #pengambilan-export-buttons .dt-button:hover,
                    #pengambilan-export-buttons .dt-button:focus,
                    .dt-buttons .dt-button:hover,
                    .dt-buttons .dt-button:focus {
                        background: #e7eaec;
                        color: #222;
                        border-color: #d2d2d2;
                    }
                    #pengambilan-export-buttons .dt-button:last-child,
                    .dt-buttons .dt-button:last-child {
                        margin-right: 0;
                    }
                </style>
                <form id="sortForm" method="get" style="display:none;">
                    <input type="hidden" name="sort" id="sortInput" value="<?= htmlspecialchars($sort) ?>">
                </form>
                <script>
                    function setSort(val) {
                        document.getElementById('sortInput').value = val;
                        document.getElementById('sortForm').submit();
                    }
                </script>
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
                    <!-- Added table view above the card list -->
                    <?php if ($dataList): ?>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered table-striped align-middle dataTables-example" style="display: none">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Jumlah (Kg)</th>
                                        <th>Kategori</th>
                                        <th>Status Pengambilan</th>
                                        <th>Waktu Ambil</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($dataList as $data): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($data['nama']) ?></td>
                                            <td><?= htmlspecialchars($data['jumlah_kg']) ?></td>
                                            <td>
                                                <?php
                                                    if (!empty($data['is_admin'])) {
                                                        $kategori = 'Admin';
                                                    } elseif (!empty($data['is_panitia'])) {
                                                        $kategori = 'Panitia';
                                                    } elseif (!empty($data['is_qurban'])) {
                                                        $kategori = 'Qurban';
                                                    } elseif (!empty($data['is_warga'])) {
                                                        $kategori = 'Warga';
                                                    } else {
                                                        $kategori = '-';
                                                    }
                                                    echo htmlspecialchars($kategori);
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                    $status = ($data['status_ambil'] ?? 0) == 1 ? 'Sudah Diambil' : 'Belum Diambil';
                                                    $badgeClass = ($status === 'Sudah Diambil')
                                                        ? 'badge badge-success rounded-pill px-3 py-2'
                                                        : 'badge badge-warning rounded-pill px-3 py-2';
                                                ?>
                                                <span class="<?= $badgeClass ?>"><?= $status ?></span>
                                            </td>
                                            <td>
                                                <?= htmlspecialchars($data['waktu_ambil']) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                    <?php if ($dataList): ?>
                        <?php foreach ($dataList as $data): ?>
                            <div class="card-status d-flex justify-content-between align-items-center border rounded p-4 mb-4">
                                <div>
                                    <h5 class="fw-bold mb-1"><?= htmlspecialchars($data['nama']) ?></h5>
                                    <p class="text-muted mb-0">
                                        <?= htmlspecialchars($data['jumlah_kg']) ?> Kg - Kategori: 
                                        <?php
                                            // Determine role from flags in warga table
                                            if (!empty($data['is_admin'])) {
                                                $kategori = 'Admin';
                                            } elseif (!empty($data['is_panitia'])) {
                                                $kategori = 'Panitia';
                                            } elseif (!empty($data['is_qurban'])) {
                                                $kategori = 'Qurban';
                                            } elseif (!empty($data['is_warga'])) {
                                                $kategori = 'Warga';
                                            } else {
                                                $kategori = '-';
                                            }
                                            echo htmlspecialchars($kategori);
                                        ?>
                                    </p>
                                </div>
                                    <div class="d-flex align-items-center gap-5">
                                    <?php
                                        $status = ($data['status_ambil'] ?? 0) == 1 ? 'Sudah Diambil' : 'Belum Diambil';
                                        $badgeClass = ($status === 'Sudah Diambil')
                                            ? 'badge badge-success rounded-pill px-3 py-2'
                                            : 'badge badge-warning rounded-pill px-3 py-2';
                                    ?>
                                    <span class="<?= $badgeClass ?>"><?= $status ?></span>
                                    <?php if (!empty($data['waktu_ambil']) && $status === 'Sudah Diambil'): ?>
                                        <?php
                                            $waktu = strtotime($data['waktu_ambil']);
                                            $waktu_fmt = date('d M Y H:i', $waktu);
                                        ?>
                                        <span class="ms-2" style="font-size:0.97em;color:#388e3c;">
                                            <i class="fa fa-clock-o"></i>
                                            <span><?= htmlspecialchars($waktu_fmt) ?></span>
                                        </span>
                                    <?php endif; ?>
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
                        display: none;
                    }
                    .qr-upload-btn-wrapper {
                        display: none;
                    }
                </style>
                <div style="width:100%;">
                    <!-- Removed dropbox and upload button -->
                    <div id="qrImagePreview" style="margin-top:18px; text-align:center;"></div>
                    <div style="margin-top:0; text-align:center;">
                        <video id="qrVideo" width="320" height="240" style="border-radius:12px; border:2px solid #bbb; background:#fafafa;" autoplay playsinline></video>
                        <div style="font-size:0.95em;color:#888;margin-top:6px;">Scan langsung dengan kamera</div>
                    </div>
                    <div id="qrScanResult" style="margin-top:18px; color:#333;"></div>
                </div>
                <script src="https://unpkg.com/jsqr/dist/jsQR.js"></script>
                <script>
                    const resultDiv = document.getElementById('qrScanResult');
                    const video = document.getElementById('qrVideo');
                    let scanning = false;
                    let lastToken = null;
                    let retryTimeout = null;

                    function resetScanner(delay = 2000) {
                        if (retryTimeout) clearTimeout(retryTimeout);
                        retryTimeout = setTimeout(() => {
                            lastToken = null;
                            scanning = true;
                            requestAnimationFrame(tick);
                        }, delay);
                    }

                    function handleQRData(token) {
                        if (lastToken === token) return; // avoid duplicate scans
                        lastToken = token;
                        resultDiv.innerHTML = "<b>QR Code ditemukan:</b><br><span style='word-break:break-all; color:#888; font-size:0.95em;'>" + token + "</span>";
                        // Fetch data from PHP using AJAX
                        fetch('qr/read?token=' + encodeURIComponent(token))
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
                                        resultDiv.innerHTML += createAmbilButton(token);
                                    }
                                    resetScanner(2500); // retry after 2.5s for successful scan
                                } else {
                                    resultDiv.innerHTML += "<hr><span style='color:red;'>Data tidak ditemukan untuk token QR ini.</span>";
                                    resetScanner(2000); // retry after 2s for failed scan
                                }
                            })
                            .catch(err => {
                                resultDiv.innerHTML += "<hr><span style='color:red;'>Gagal mengambil data dari server.</span>";
                                resetScanner(2000); // retry after 2s for error
                            });
                    }

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

                    function startCamera() {
                        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                            return;
                        }
                        navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
                            .then(function(stream) {
                                video.srcObject = stream;
                                video.setAttribute("playsinline", true); // required for iOS
                                scanning = true;
                                requestAnimationFrame(tick);
                            })
                            .catch(function(err) {
                                // Optionally show error to user
                            });
                    }

                    function tick() {
                        if (!scanning) return;
                        if (video.readyState === video.HAVE_ENOUGH_DATA) {
                            const canvas = document.createElement('canvas');
                            canvas.width = video.videoWidth;
                            canvas.height = video.videoHeight;
                            const ctx = canvas.getContext('2d');
                            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                            const code = jsQR(imageData.data, canvas.width, canvas.height);
                            if (code) {
                                scanning = false;
                                handleQRData(code.data);
                                return; // don't call tick again until reset
                            }
                        }
                        if (scanning) {
                            requestAnimationFrame(tick);
                        }
                    }

                    startCamera();
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

<script>
var table = $('.dataTables-example').DataTable({
    pageLength: 25,
    responsive: true,
    dom: 'B', // Show buttons, length, filter, table, info, pagination (but we'll move them)
    buttons: [
        { extend: 'copy' },
        { extend: 'csv' },
        { extend: 'excel', title: 'Data Pembagian Qurban' },
        { extend: 'pdf', title: 'Data Pembagian Qurban' },
        {
            extend: 'print',
            customize: function(win) {
                $(win.document.body).addClass('white-bg');
                $(win.document.body).css('font-size', '10px');
                $(win.document.body).find('table')
                    .addClass('compact')
                    .css('font-size', 'inherit');
            }
        }
    ]
});

var $dtButtons = $('.dataTables-example').closest('.dataTables_wrapper').find('.dt-buttons');
$('#pengambilan-export-buttons').append($dtButtons);
</script>
