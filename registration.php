<?php
session_start();

// Simpan UID kosong setiap load halaman
$Write="<?php $" . "UIDresult=''; " . "echo $" . "UIDresult;" . " ?>";
file_put_contents('UIDContainer.php',$Write);

// ambil kelas aktif dari session
$kelas_id = isset($_SESSION['kelas_id']) ? $_SESSION['kelas_id'] : null;

// ambil pesan notifikasi kalau ada
$msg = $_SESSION['msg'] ?? null;
$msg_error = $_SESSION['msg_error'] ?? null;
unset($_SESSION['msg'], $_SESSION['msg_error']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Mahasiswa</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#getUID").load("UIDContainer.php");
            setInterval(function() {
                $("#getUID").load("UIDContainer.php");
            }, 500);
        });
    </script>
</head>
<body>

<?php if ($msg): ?>
<div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
  <div class="toast align-items-center bg-success-subtle border-success-subtle show" role="alert">
    <div class="d-flex">
      <div class="toast-body text-success">
        <?= htmlspecialchars($msg) ?>
      </div>
      <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>
<?php endif; ?>

<?php if ($msg_error): ?>
<div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
  <div class="toast align-items-center bg-danger-subtle border-danger-subtle show" role="alert">
    <div class="d-flex">
      <div class="toast-body text-danger">
        <?= htmlspecialchars($msg_error) ?>
      </div>
      <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>
<?php endif; ?>

<div class="container">
    <h2 class="mt-3">Registrasi Kartu Mahasiswa</h2>
    <?php include 'navbar.php'; ?>

    <div class="row justify-content-center mt-4 w-100">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <form action="tambah_mahasiswa.php" method="post">
                        <div class="row">
                            <!-- Kolom Kiri -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="getUID" class="form-label">ID Kartu</label>
                                    <textarea class="form-control" name="id" id="getUID" 
                                        placeholder="Tempelkan kartu Anda" rows="1" readonly required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nama</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">NIM</label>
                                    <input type="text" name="nim" class="form-control" required>
                                </div>
                            </div>

                            <!-- Kolom Kanan -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <select name="gender" class="form-select" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="male">Laki-laki</option>
                                        <option value="female">Perempuan</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">No. HP</label>
                                    <input type="text" name="mobile" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="kelas_id" value="<?= htmlspecialchars($kelas_id) ?>">
                        <button type="submit" class="btn btn-primary w-100 mt-3">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
