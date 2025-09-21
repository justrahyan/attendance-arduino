<?php
session_start();
require_once 'database.php';
$pdo = Database::connect();

$id       = isset($_POST['id']) ? trim($_POST['id']) : '';
$name     = isset($_POST['name']) ? trim($_POST['name']) : '';
$nim      = isset($_POST['nim']) ? trim($_POST['nim']) : '';
$gender   = isset($_POST['gender']) ? trim($_POST['gender']) : '';
$email    = isset($_POST['email']) ? trim($_POST['email']) : '';
$mobile   = isset($_POST['mobile']) ? trim($_POST['mobile']) : '';
$kelas_id = isset($_POST['kelas_id']) && $_POST['kelas_id'] !== '' ? $_POST['kelas_id'] : null;

if ($id === '' || $name === '') {
    $_SESSION['msg_error'] = "Data tidak lengkap!";
    header("Location: registration.php");
    exit;
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("SELECT id FROM table_nodemcu_rfidrc522_mysql WHERE id = ?");
    $stmt->execute([$id]);
    $exists = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$exists) {
        $sql = "INSERT INTO table_nodemcu_rfidrc522_mysql (id, name, nim, gender, email, mobile)
                VALUES (?, ?, ?, ?, ?, ?)";
        $q = $pdo->prepare($sql);
        $q->execute([$id, $name, $nim, $gender, $email, $mobile]);
        $mahasiswa_id = $id;
    } else {
        $mahasiswa_id = $exists['id'];
    }

    if (!empty($kelas_id)) {
        $chk = $pdo->prepare("SELECT id FROM table_mahasiswa_kelas WHERE mahasiswa_id = ? AND kelas_id = ?");
        $chk->execute([$mahasiswa_id, $kelas_id]);
        $relasi = $chk->fetch(PDO::FETCH_ASSOC);

        if (!$relasi) {
            $ins = $pdo->prepare("INSERT INTO table_mahasiswa_kelas (mahasiswa_id, kelas_id) VALUES (?, ?)");
            $ins->execute([$mahasiswa_id, $kelas_id]);
        }
    }

    $pdo->commit();
    $_SESSION['msg'] = "Mahasiswa berhasil ditambahkan!";
} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['msg_error'] = "Terjadi kesalahan, coba lagi!";
}

Database::disconnect();
header("Location: registration.php");
exit;
