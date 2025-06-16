<?php
require_once 'config/db.php';


$judulHalaman = 'Tambah User';

ob_start();
?>

<div class="col-lg-5">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Tambah User</h5>
        </div>
        <div class="ibox-content">
            <form class="form-horizontal" action="<?php echo $basePath . '/api/dashboard/store' ?>" method="POST">
                <p>Form untuk menambah user baru.</p>
                <div class="form-group">
                    <label class="col-lg-2 control-label">Nama</label>
                    <div class="col-lg-10">
                        <input type="text" name="nama" placeholder="Nama" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">NIK</label>
                    <div class="col-lg-10">
                        <input type="text" name="nik" class="form-control" placeholder="NIK" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">No. HP</label>
                    <div class="col-lg-10">
                        <input type="text" name="no_hp" class="form-control" placeholder="No. HP" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">Role</label>
                    <div class="col-lg-10">
                        <select name="role" class="form-control" required>
                            <option value="" disabled selected hidden>Daftar untuk bagian...</option>
                            <option value="warga">Warga</option>
                            <option value="panitia">Panitia</option>
                            <option value="berqurban">Berqurban</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">Password</label>
                    <div class="col-lg-10">
                        <input type="password" name="password" placeholder="Password" class="form-control" required>
                    </div>
                </div>

                <!-- Tambahkan input untuk jenis hewan jika role = berqurban -->
                <div class="form-group" id="jenis_hewan_group" style="display:none;">
                    <label class="col-lg-2 control-label">Jenis Hewan</label>
                    <div class="col-lg-10">
                        <select name="jenis_hewan" class="form-control">
                            <option value="Sapi">Sapi</option>
                            <option value="Kambing">Kambing</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-lg-offset-2 col-lg-10">
                        <button class="btn btn-sm btn-white" type="submit">Tambah User</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.querySelector('select[name="role"]').addEventListener('change', function() {
        var role = this.value;
        var jenisHewanGroup = document.getElementById('jenis_hewan_group');
        var kontribusiGroup = document.getElementById('kontribusi_group');

        if (role === 'berqurban') {
            jenisHewanGroup.style.display = 'block';
            kontribusiGroup.style.display = 'block';
        } else {
            jenisHewanGroup.style.display = 'none';
            kontribusiGroup.style.display = 'none';
        }
    });
</script>

<?php
$content = ob_get_clean();
include_once __DIR__ . '/../templates/layout.php';
?>