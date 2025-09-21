<?php
session_start();

// Simpan UID kosong setiap load halaman
$Write="<?php $" . "UIDresult=''; " . "echo $" . "UIDresult;" . " ?>";
file_put_contents('UIDContainer.php',$Write);

// ambil kelas aktif dari session
$kelas_id = isset($_SESSION['kelas_id']) ? $_SESSION['kelas_id'] : null;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Mahasiswa</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
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
    <div class="container">
        <h2 class="mt-3">Registrasi Kartu Mahasiswa</h2>
        <?php include 'navbar.php'; ?>

        <div class="row justify-content-center mt-4">
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-success text-white text-center">
                        <h4 class="mb-0">Form Registrasi</h4>
                    </div>
                    <div class="card-body">
                        <form action="tambah_mahasiswa.php" method="post">
                            <!-- ID RFID -->
                            <div class="mb-3">
                                <label for="getUID" class="form-label">ID Kartu</label>
                                <textarea class="form-control" name="id" id="getUID" 
                                    placeholder="Tempelkan kartu Anda" rows="1" readonly required></textarea>
                            </div>

                            <!-- Nama -->
                            <div class="mb-3">
                                <label class="form-label">Nama</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>

                            <!-- NIM -->
                            <div class="mb-3">
                                <label class="form-label">NIM</label>
                                <input type="text" name="nim" class="form-control" required>
                            </div>

                            <!-- Jenis Kelamin -->
                            <div class="mb-3">
                                <label class="form-label">Jenis Kelamin</label>
                                <select name="gender" class="form-select" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="male">Laki-laki</option>
                                    <option value="female">Perempuan</option>
                                </select>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>

                            <!-- Nomor HP -->
                            <div class="mb-3">
                                <label class="form-label">No. HP</label>
                                <input type="text" name="mobile" class="form-control" required>
                            </div>

                            <!-- Hidden Kelas ID -->
                            <input type="hidden" name="kelas_id" value="<?= htmlspecialchars($kelas_id) ?>">

                            <!-- Tombol Submit -->
                            <button type="submit" class="btn btn-success w-100">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
