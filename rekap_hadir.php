<?php
session_start();
require_once 'database.php';
$pdo = Database::connect();

$kelas_id = $_SESSION['kelas_id'] ?? null;
$tanggal = $_GET['tanggal'] ?? null;

// --- Ambil daftar tanggal unik ---
if ($kelas_id) {
    $sql_tanggal = "SELECT DATE(waktu_absen) as tgl 
                    FROM daftar_hadir 
                    WHERE kelas_id = ? 
                    GROUP BY DATE(waktu_absen)
                    ORDER BY tgl DESC";
    $stmt_tanggal = $pdo->prepare($sql_tanggal);
    $stmt_tanggal->execute([$kelas_id]);
    $tanggal_list = $stmt_tanggal->fetchAll(PDO::FETCH_ASSOC);
} else {
    $tanggal_list = [];
}

// --- Ambil data hadir sesuai tanggal (untuk modal) ---
$rows = [];
if ($kelas_id && $tanggal) {
    $sql = "SELECT dh.*, u.name, k.nama_kelas 
            FROM daftar_hadir dh
            JOIN table_nodemcu_rfidrc522_mysql u ON dh.user_id = u.id
            JOIN table_kelas k ON dh.kelas_id = k.id
            WHERE dh.kelas_id = ? AND DATE(dh.waktu_absen) = ?
            ORDER BY dh.waktu_absen DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$kelas_id, $tanggal]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

Database::disconnect();

// --- Download CSV ---
if (isset($_GET['download']) && $tanggal && $kelas_id) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename="rekap_'.$tanggal.'.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['No', 'Nama', 'Kelas', 'Waktu Absen', 'Status']);

    $no = 1;
    foreach ($rows as $row) {
        fputcsv($output, [$no++, $row['name'], $row['nama_kelas'], $row['waktu_absen'], $row['status']]);
    }

    fclose($output);
    exit;
}

// --- Ringkasan Kehadiran ---
$total_mahasiswa = count($rows);
$hadir = count(array_filter($rows, fn($r) => $r['status'] === 'Hadir'));
$terlambat = count(array_filter($rows, fn($r) => $r['status'] === 'Terlambat'));
$tidak_hadir = count(array_filter($rows, fn($r) => $r['status'] === 'Tidak Hadir'));
?>
<!DOCTYPE html>
<html>
<head>
    <title>Rekap Kehadiran</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">
<div class="container">
    <h2 class="mt-3">Rekap Kehadiran Harian</h2>
    <?php include 'navbar.php'; ?>

    <!-- Daftar Tanggal -->
    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <?php if (empty($tanggal_list)): ?>
                <p class="text-muted">Belum ada data kehadiran.</p>
            <?php else: ?>
                <div class="list-group">
                    <?php foreach ($tanggal_list as $tgl): ?>
                        <div class="d-flex justify-content-between align-items-center list-group-item">
                            <span><?= htmlspecialchars($tgl['tgl']) ?></span>
                            <div>
                                <!-- Tombol buka modal -->
                                <a href="?tanggal=<?= $tgl['tgl'] ?>" class="btn btn-sm btn-outline-primary">Lihat</a>
                                <a href="?tanggal=<?= $tgl['tgl'] ?>&download=1" class="btn btn-sm btn-success">Download CSV</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Kehadiran -->
<?php if ($tanggal): ?>
<div class="modal fade show" id="rekapModal" tabindex="-1" style="display:block; background:rgba(0,0,0,0.5);">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-secondary text-white">
        <h5 class="modal-title">📌 Rekap Kehadiran Tanggal: <?= htmlspecialchars($tanggal) ?></h5>
        <a href="rekap_hadir.php" class="btn-close btn-close-white"></a>
      </div>
      <div class="modal-body">
        <!-- Ringkasan -->
        <div class="row mb-4 text-center">
            <div class="col-md-3"><div class="p-2 border rounded"><h6>Total</h6><h4><?= $total_mahasiswa ?></h4></div></div>
            <div class="col-md-3"><div class="p-2 border rounded"><h6 class="text-success">Hadir</h6><h4 class="text-success"><?= $hadir ?></h4></div></div>
            <div class="col-md-3"><div class="p-2 border rounded"><h6 class="text-warning">Terlambat</h6><h4 class="text-warning"><?= $terlambat ?></h4></div></div>
            <div class="col-md-3"><div class="p-2 border rounded"><h6 class="text-danger">Tidak Hadir</h6><h4 class="text-danger"><?= $tidak_hadir ?></h4></div></div>
        </div>

        <!-- Tabel Kehadiran -->
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Waktu Absen</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rows)): ?>
                    <tr><td colspan="5" class="text-center text-muted">Tidak ada data kehadiran.</td></tr>
                <?php else: $no=1; foreach ($rows as $row): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['nama_kelas']) ?></td>
                        <td><?= htmlspecialchars($row['waktu_absen']) ?></td>
                        <td>
                            <span class="badge 
                                <?= $row['status'] === 'Hadir' ? 'bg-success' : 
                                   ($row['status'] === 'Terlambat' ? 'bg-warning text-dark' : 'bg-danger') ?>">
                                <?= htmlspecialchars($row['status']) ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <a href="rekap_hadir.php" class="btn btn-secondary">Tutup</a>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>
</body>
</html>
