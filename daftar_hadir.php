<?php
session_start();
require_once 'database.php';
$pdo = Database::connect();

$kelas_id = $_SESSION['kelas_id'] ?? null;

if ($kelas_id) {
    // Ambil data hanya untuk kelas aktif dan tanggal hari ini
    $sql = "SELECT dh.*, u.name, k.nama_kelas 
            FROM daftar_hadir dh
            JOIN table_nodemcu_rfidrc522_mysql u ON dh.user_id = u.id
            JOIN table_kelas k ON dh.kelas_id = k.id
            WHERE dh.kelas_id = ? 
              AND DATE(dh.waktu_absen) = CURDATE()
            ORDER BY dh.waktu_absen DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$kelas_id]);
} else {
    // Jika tidak ada kelas aktif, kosongkan hasil
    $stmt = $pdo->query("SELECT * FROM daftar_hadir WHERE 1=0");
}

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
Database::disconnect();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Hadir</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h2 class="mt-3">Daftar Hadir Hari Ini</h2>
    <?php include 'navbar.php'; ?>
    <table class="table table-striped table-bordered mt-3">
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
            <?php 
            $no = 1;
            foreach($rows as $row){ ?>
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
            <?php } ?>
            <?php if (empty($rows)) { ?>
                <tr>
                    <td colspan="5" class="text-center">Belum ada absensi untuk hari ini.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>
