<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'database.php';

$id = $_GET['id'] ?? null;

$pdo = Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Default data
$data = [
    'id'     => "--------",
    'name'   => "--------",
    'gender' => "--------",
    'email'  => "--------",
    'mobile' => "--------",
];
$status = "--------";
$waktu_absen = "--------";
$kelas_nama = "--------";
$msg = null;

if ($id) {
    // cek apakah UID ada di tabel mahasiswa
    $sql = "SELECT * FROM table_nodemcu_rfidrc522_mysql WHERE id = ?";
    $q = $pdo->prepare($sql);
    $q->execute([$id]);
    $result = $q->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        // kartu memang sudah terdaftar
        $data = $result;
        $msg = "Kartu sudah terdaftar di sistem.";

        // Ambil kelas aktif dari session
        $kelas_id = $_SESSION['kelas_id'] ?? null;

        if ($kelas_id) {
            // cek apakah user ini memang terdaftar di kelas tsb
            $cekRelasi = $pdo->prepare("SELECT id FROM table_mahasiswa_kelas 
                                        WHERE mahasiswa_id = ? AND kelas_id = ?");
            $cekRelasi->execute([$data['id'], $kelas_id]);
            $relasi = $cekRelasi->fetch();

            if (!$relasi) {
                // jika belum terdaftar di kelas aktif
                $msg = "Anda belum terdaftar di kelas ini!";
            } else {
                // user memang terdaftar → boleh absen
                $kelasSql = "SELECT * FROM table_kelas WHERE id = ? LIMIT 1";
                $kelasQ = $pdo->prepare($kelasSql);
                $kelasQ->execute([$kelas_id]);
                $kelas = $kelasQ->fetch(PDO::FETCH_ASSOC);

                if ($kelas) {
                    $kelas_nama = $kelas['nama_kelas'];

                    date_default_timezone_set('Asia/Makassar'); // WITA (UTC+8)
                    $now = date("H:i:s");
                    $waktu_absen = date("Y-m-d H:i:s");
                    $status = ($now <= $kelas['jam_masuk']) ? "Tepat Waktu" : "Terlambat";

                    // cek apakah sudah absen hari ini
                    $today = date("Y-m-d");
                    $cek = $pdo->prepare("SELECT id FROM daftar_hadir 
                                          WHERE user_id = ? AND kelas_id = ? 
                                          AND DATE(waktu_absen) = ?");
                    $cek->execute([$data['id'], $kelas_id, $today]);
                    $already = $cek->fetch();

                    if ($already) {
                        $msg = "Anda sudah absen hari ini.";
                    } else {
                        $insert = "INSERT INTO daftar_hadir (user_id, kelas_id, waktu_absen, status) 
                                   VALUES (?, ?, ?, ?)";
                        $stmt = $pdo->prepare($insert);
                        $stmt->execute([$data['id'], $kelas_id, $waktu_absen, $status]);
                        $msg = "Absensi berhasil dicatat!";
                    }
                }
            }
        }
    } else {
        // kartu belum terdaftar di sistem sama sekali
        $msg = "Kartu anda belum terdaftar di sistem !!!";
    }
}

Database::disconnect();
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<div class="container">
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row">
                <!-- Kolom kiri -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">ID Kartu</label>
                        <input type="text" class="form-control" readonly value="<?= $data['id'] ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" class="form-control" readonly value="<?= $data['name'] ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">NIM</label>
                        <input type="text" class="form-control" readonly value="<?= $data['nim'] ?? '--------' ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jenis Kelamin</label>
                        <input type="text" class="form-control" readonly value="<?= $data['gender'] ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="text" class="form-control" readonly value="<?= $data['email'] ?>">
                    </div>
                </div>

                <!-- Kolom kanan -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">No. HP</label>
                        <input type="text" class="form-control" readonly value="<?= $data['mobile'] ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kelas</label>
                        <input type="text" class="form-control" readonly value="<?= $kelas_nama ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <input type="text" class="form-control" readonly value="<?= $status ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Waktu Absen</label>
                        <input type="text" class="form-control" readonly value="<?= $waktu_absen ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($msg): ?>
<!-- Toast Notification -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
  <div id="liveToast" 
       class="toast align-items-center bg-success-subtle border-success-subtle show" 
       role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body text-success">
        <?= htmlspecialchars($msg) ?>
      </div>
      <button type="button" class="btn-close me-2 m-auto" 
              data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>
<?php endif; ?>

