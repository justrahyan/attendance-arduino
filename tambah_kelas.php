<?php
session_start();
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama_kelas'] ?? '';
    $masuk = $_POST['jam_masuk'] ?? '';
    $selesai = $_POST['jam_selesai'] ?? '';

    if (!empty($nama) && !empty($masuk) && !empty($selesai)) {
        $pdo = Database::connect();
        $sql = "INSERT INTO table_kelas (nama_kelas, jam_masuk, jam_selesai) VALUES (?, ?, ?)";
        $q = $pdo->prepare($sql);
        $q->execute([$nama, $masuk, $selesai]);
        Database::disconnect();

        $_SESSION['msg'] = 'Kelas baru berhasil ditambahkan.';
    } else {
        $_SESSION['msg_error'] = 'Semua kolom wajib diisi.';
    }
}
header("Location: daftar_kelas.php");
exit;
