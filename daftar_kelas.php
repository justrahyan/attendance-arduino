<?php
include_once 'database.php';
$pdo = Database::connect();

// Ambil data kelas + jumlah mahasiswa di masing2 kelas
$sql = "
    SELECT k.*, COUNT(mk.id) AS jumlah_mahasiswa
    FROM table_kelas k
    LEFT JOIN table_mahasiswa_kelas mk ON k.id = mk.kelas_id
    GROUP BY k.id
    ORDER BY k.id ASC
";
$q = $pdo->query($sql);
$data_kelas = $q->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Kelas</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
    <h2 class="mt-3">Daftar Kelas</h2>
    <?php include 'navbar.php'; ?>
    <div class="text-start my-3">
        <a href="tambah_kelas.php" class="btn btn-success">+ Tambah Kelas</a>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Kelas</th>
                <th>Jam Masuk</th>
                <th>Jam Selesai</th>
                <th>Jumlah Mahasiswa</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data_kelas as $row): ?> 
                <tr>
                    <td><?= htmlspecialchars($row['id']); ?></td>
                    <td><?= htmlspecialchars($row['nama_kelas']); ?></td>
                    <td><?= htmlspecialchars($row['jam_masuk']); ?></td>
                    <td><?= htmlspecialchars($row['jam_selesai']); ?></td>
                    <td><?= htmlspecialchars($row['jumlah_mahasiswa']); ?></td>
                    <td>
                        <a href="edit_kelas.php?id=<?= $row['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                        <a href="hapus_kelas.php?id=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</a>
                        <a href="masuk_kelas.php?id=<?= $row['id']; ?>" class="btn btn-success btn-sm">Masuk Kelas</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
<?php Database::disconnect(); ?>
