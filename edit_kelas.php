<?php
session_start();
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $nama = $_POST['nama_kelas'] ?? '';
    $masuk = $_POST['jam_masuk'] ?? '';
    $selesai = $_POST['jam_selesai'] ?? '';

    if (!empty($nama) && !empty($masuk) && !empty($selesai)) {
        $pdo = Database::connect();
        $sql = "UPDATE table_kelas SET nama_kelas=?, jam_masuk=?, jam_selesai=? WHERE id=?";
        $q = $pdo->prepare($sql);
        $q->execute([$nama, $masuk, $selesai, $id]);
        Database::disconnect();

        $_SESSION['msg'] = 'Kelas berhasil diperbarui.';
    } else {
        $_SESSION['msg_error'] = 'Semua kolom wajib diisi.';
    }
}
header("Location: daftar_kelas.php");
exit;
