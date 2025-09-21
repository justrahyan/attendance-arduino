<?php
session_start();
require_once 'database.php';
$pdo = Database::connect();

$kelas_id = $_GET['kelas_id'] ?? ($_SESSION['kelas_id'] ?? null);

if (!$kelas_id) {
    header("Location: home.php");
    exit;
}

// Ambil data kelas
$sql_kelas = "SELECT * FROM table_kelas WHERE id = ?";
$stmt = $pdo->prepare($sql_kelas);
$stmt->execute([$kelas_id]);
$kelas = $stmt->fetch(PDO::FETCH_ASSOC);

// Ambil ringkasan kehadiran hari ini
$sql_hadir = "SELECT COUNT(*) FROM daftar_hadir WHERE kelas_id = ? AND DATE(waktu_absen) = CURDATE() AND status = 'Hadir'";
$stmt = $pdo->prepare($sql_hadir);
$stmt->execute([$kelas_id]);
$hadir = $stmt->fetchColumn();

$sql_terlambat = "SELECT COUNT(*) FROM daftar_hadir WHERE kelas_id = ? AND DATE(waktu_absen) = CURDATE() AND status = 'Terlambat'";
$stmt = $pdo->prepare($sql_terlambat);
$stmt->execute([$kelas_id]);
$terlambat = $stmt->fetchColumn();

$sql_total = "SELECT COUNT(*) FROM daftar_hadir WHERE kelas_id = ? AND DATE(waktu_absen) = CURDATE()";
$stmt = $pdo->prepare($sql_total);
$stmt->execute([$kelas_id]);
$total_hadir = $stmt->fetchColumn();

$sql_mahasiswa = "
    SELECT COUNT(*) 
    FROM table_mahasiswa_kelas mk
    WHERE mk.kelas_id = ?
";
$stmt = $pdo->prepare($sql_mahasiswa);
$stmt->execute([$kelas_id]);
$jumlah_mahasiswa = $stmt->fetchColumn();

Database::disconnect();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard <?= htmlspecialchars($kelas['nama_kelas']) ?></title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container">
    <h2 class="mt-3">Dashboard: <?= htmlspecialchars($kelas['nama_kelas']) ?></h2>
    <?php include 'navbar.php'; ?>

    <div class="row mt-3">
        <div class="col-md-6">
            <div class="card bg-danger-subtle border-danger-subtle border-2 text-center shadow-sm p-3">
                <div class="card-body">
                    <div class="icon">👨‍🎓</div>
                    <h5>Jumlah Mahasiswa</h5>
                    <h2><?= $jumlah_mahasiswa ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-success-subtle border-success-subtle border-2 text-center shadow-sm p-3">
                <div class="card-body">
                    <div class="icon">✅</div>
                    <h5>Total Absen Hari Ini</h5>
                    <h2><?= $total_hadir ?></h2>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
