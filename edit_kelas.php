<?php
require 'database.php';
$id = $_GET['id'];

$pdo = Database::connect();
$sql = "SELECT * FROM table_kelas WHERE id = ?";
$q = $pdo->prepare($sql);
$q->execute([$id]);
$data = $q->fetch(PDO::FETCH_ASSOC);

if ($_POST) {
    $nama = $_POST['nama_kelas'];
    $masuk = $_POST['jam_masuk'];
    $selesai = $_POST['jam_selesai'];

    $sql = "UPDATE table_kelas SET nama_kelas=?, jam_masuk=?, jam_selesai=? WHERE id=?";
    $q = $pdo->prepare($sql);
    $q->execute([$nama, $masuk, $selesai, $id]);
    Database::disconnect();
    header("Location: daftar_kelas.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Edit Kelas</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h2 class="mt-3">Edit Kelas</h2>
    <form method="post">
        <div class="mb-3">
            <label>Nama Kelas</label>
            <input type="text" name="nama_kelas" class="form-control" value="<?= $data['nama_kelas']; ?>" required>
        </div>
        <div class="mb-3">
            <label>Jam Masuk</label>
            <input type="time" name="jam_masuk" class="form-control" value="<?= $data['jam_masuk']; ?>" required>
        </div>
        <div class="mb-3">
            <label>Jam Selesai</label>
            <input type="time" name="jam_selesai" class="form-control" value="<?= $data['jam_selesai']; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="daftar_kelas.php" class="btn btn-secondary">Batal</a>
    </form>
</div>
</body>
</html>
