<?php
require 'database.php';

if ($_POST) {
    $nama = $_POST['nama_kelas'];
    $masuk = $_POST['jam_masuk'];
    $selesai = $_POST['jam_selesai'];

    $pdo = Database::connect();
    $sql = "INSERT INTO table_kelas (nama_kelas, jam_masuk, jam_selesai) VALUES (?, ?, ?)";
    $q = $pdo->prepare($sql);
    $q->execute([$nama, $masuk, $selesai]);
    Database::disconnect();
    header("Location: daftar_kelas.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tambah Kelas</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h2 class="mt-3">Tambah Kelas</h2>
    <form method="post">
        <div class="mb-3">
            <label>Nama Kelas</label>
            <input type="text" name="nama_kelas" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Jam Masuk</label>
            <input type="time" name="jam_masuk" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Jam Selesai</label>
            <input type="time" name="jam_selesai" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="daftar_kelas.php" class="btn btn-secondary">Batal</a>
    </form>
</div>
</body>
</html>
