<?php
session_start();
include_once 'database.php';
$pdo = Database::connect();

if (isset($_GET['id'])) {
    $kelas_id = $_GET['id'];

    try {
        $pdo->beginTransaction();

        // Hapus daftar hadir
        $stmt = $pdo->prepare("DELETE FROM daftar_hadir WHERE kelas_id = ?");
        $stmt->execute([$kelas_id]);

        // Hapus relasi mahasiswa
        $stmt = $pdo->prepare("DELETE FROM table_mahasiswa_kelas WHERE kelas_id = ?");
        $stmt->execute([$kelas_id]);

        // Hapus kelas
        $stmt = $pdo->prepare("DELETE FROM table_kelas WHERE id = ?");
        $stmt->execute([$kelas_id]);

        $pdo->commit();
        $_SESSION['msg'] = 'Kelas berhasil dihapus.';
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['msg_error'] = "Gagal menghapus: " . $e->getMessage();
    }
}
Database::disconnect();
header("Location: daftar_kelas.php");
exit;
