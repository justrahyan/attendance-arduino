<?php
require_once 'database.php';
$pdo = Database::connect();
$sql = "SELECT dh.*, u.name, k.nama_kelas 
        FROM daftar_hadir dh
        JOIN table_nodemcu_rfidrc522_mysql u ON dh.user_id = u.id
        JOIN table_kelas k ON dh.kelas_id = k.id
        ORDER BY dh.waktu_absen DESC";
$stmt = $pdo->query($sql);
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
    <h2 class="mt-3">Daftar Hadir</h2>
    <?php include 'navbar.php'; ?>
    <table class="table table-bordered mt-3">
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Kelas</th>
            <th>Waktu Absen</th>
            <th>Status</th>
        </tr>
        <?php 
        $no = 1;
        foreach($rows as $row){ ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['nama_kelas']) ?></td>
                <td><?= htmlspecialchars($row['waktu_absen']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
            </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>
