<?php
require_once 'database.php';
$pdo = Database::connect();

// ambil input (trim untuk kebersihan)
$id     = isset($_POST['id']) ? trim($_POST['id']) : '';
$name   = isset($_POST['name']) ? trim($_POST['name']) : '';
$nim    = isset($_POST['nim']) ? trim($_POST['nim']) : '';
$gender = isset($_POST['gender']) ? trim($_POST['gender']) : '';
$email  = isset($_POST['email']) ? trim($_POST['email']) : '';
$mobile = isset($_POST['mobile']) ? trim($_POST['mobile']) : '';
$kelas_id = isset($_POST['kelas_id']) && $_POST['kelas_id'] !== '' ? $_POST['kelas_id'] : null;

// validasi sederhana
if ($id === '' || $name === '') {
    // kalau mau, redirect kembali dengan pesan error
    header("Location: registration.php");
    exit;
}

try {
    // mulai transaksi
    $pdo->beginTransaction();

    // cek apakah mahasiswa (UID) sudah ada
    $stmt = $pdo->prepare("SELECT id FROM table_nodemcu_rfidrc522_mysql WHERE id = ?");
    $stmt->execute([$id]);
    $exists = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$exists) {
        // insert mahasiswa baru (ikut menyimpan nim)
        $sql = "INSERT INTO table_nodemcu_rfidrc522_mysql (id, name, nim, gender, email, mobile)
                VALUES (?, ?, ?, ?, ?, ?)";
        $q = $pdo->prepare($sql);
        $q->execute([$id, $name, $nim, $gender, $email, $mobile]);

        // karena id adalah UID (bukan auto-increment), gunakan $id sebagai mahasiswa_id
        $mahasiswa_id = $id;
    } else {
        // sudah ada => pakai id yang ada (UID)
        $mahasiswa_id = $exists['id'];

        // opsional: update data lain jika ingin menimpa (tidak wajib)
        // $upd = $pdo->prepare("UPDATE table_nodemcu_rfidrc522_mysql SET name=?, nim=?, gender=?, email=?, mobile=? WHERE id=?");
        // $upd->execute([$name, $nim, $gender, $email, $mobile, $mahasiswa_id]);
    }

    // jika ada kelas aktif (kelas_id diberikan), masukkan ke tabel relasi jika belum ada
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
} catch (Exception $e) {
    $pdo->rollBack();
    // debug: (jangan tampilkan di produksi)
    // echo "Error: " . $e->getMessage();
    // redirect balik ke halaman registrasi
    header("Location: registration.php");
    exit;
}

Database::disconnect();
header("Location: user data.php");
exit;
