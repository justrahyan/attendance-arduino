<?php
session_start();
require_once 'database.php';

// Menulis ulang UIDContainer.php (opsional, bisa dipertahankan)
$Write="<?php $" . "UIDresult=''; " . "echo $" . "UIDresult;" . " ?>";
file_put_contents('UIDContainer.php',$Write);

$pdo = Database::connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_user') {
    $id     = $_POST['id'];
    $name   = $_POST['name'];
    $nim    = $_POST['nim'];
    $gender = $_POST['gender'];
    $email  = $_POST['email'];
    $mobile = $_POST['mobile'];

    $sql = "UPDATE table_nodemcu_rfidrc522_mysql SET name = ?, nim = ?, gender = ?, email = ?, mobile = ? WHERE id = ?";
    $q = $pdo->prepare($sql);
    $q->execute([$name, $nim, $gender, $email, $mobile, $id]);

    $_SESSION['msg'] = "Data mahasiswa berhasil diperbarui!";
    header("Location: user data.php");
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'delete_user' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM table_nodemcu_rfidrc522_mysql WHERE id = ?";
    $q = $pdo->prepare($sql);
    $q->execute([$id]);

    $_SESSION['msg'] = "Data mahasiswa berhasil dihapus!";
    header("Location: user data.php");
    exit;
}
$msg = $_SESSION['msg'] ?? null;
unset($_SESSION['msg']);

// Cek kelas aktif
$nama_kelas = "Semua Mahasiswa";
if (isset($_SESSION['kelas_id'])) {
    $kelas_id = $_SESSION['kelas_id'];
    $stmt = $pdo->prepare("SELECT nama_kelas FROM table_kelas WHERE id = ?");
    $stmt->execute([$kelas_id]);
    $kelas = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($kelas) {
        $nama_kelas = $kelas['nama_kelas'];
    }
}

// Ambil data mahasiswa
if (isset($_SESSION['kelas_id'])) {
    $kelas_id = $_SESSION['kelas_id'];
    $sql = "SELECT m.* FROM table_nodemcu_rfidrc522_mysql m
            INNER JOIN table_mahasiswa_kelas mk ON m.id = mk.mahasiswa_id
            WHERE mk.kelas_id = ? ORDER BY m.name ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$kelas_id]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $sql = "SELECT * FROM table_nodemcu_rfidrc522_mysql ORDER BY name ASC";
    $result = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
    <title>User Data</title>
</head>
<body>

<?php if ($msg): ?>
<div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
  <div class="toast align-items-center bg-success-subtle border-success-subtle show" role="alert">
    <div class="d-flex">
      <div class="toast-body text-success">
        <?= htmlspecialchars($msg) ?>
      </div>
      <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>
<?php endif; ?>

<div class="container">
    <h2 class="mt-3">Daftar Mahasiswa: <?= htmlspecialchars($nama_kelas) ?></h2>
    <?php include 'navbar.php'; ?>
    
    <table class="table table-striped table-bordered w-100 mt-3">
        <thead class="table-dark">
            <tr>
                <th>ID Kartu</th>
                <th>Nama</th>
                <th>NIM</th>
                <th>Jenis Kelamin</th>
                <th>Email</th>
                <th>No HP</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($result as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['nim']) ?></td>
                <td><?= ($row['gender'] === 'Male') ? 'Laki-laki' : 'Perempuan' ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['mobile']) ?></td>
                <td>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditUser<?= $row['id'] ?>">Edit</button>
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalHapusUser<?= $row['id'] ?>">Hapus</button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php foreach ($result as $row): ?>
<!-- Modal Edit -->
<div class="modal fade" id="modalEditUser<?= $row['id'] ?>" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="user data.php" method="post">
        <div class="modal-header">
          <h5 class="modal-title">Edit Data Mahasiswa</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="action" value="update_user">
          <input type="hidden" name="id" value="<?= $row['id'] ?>">

          <div class="mb-3">
            <label class="form-label">ID Kartu</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($row['id']) ?>" disabled>
          </div>
          <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($row['name']) ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">NIM</label>
            <input type="text" class="form-control" name="nim" value="<?= htmlspecialchars($row['nim']) ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Jenis Kelamin</label>
            <select class="form-select" name="gender">
              <option value="Male" <?= ($row['gender'] == 'Male') ? 'selected' : '' ?>>Laki-laki</option>
              <option value="Female" <?= ($row['gender'] == 'Female') ? 'selected' : '' ?>>Perempuan</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($row['email']) ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">No. HP</label>
            <input type="text" class="form-control" name="mobile" value="<?= htmlspecialchars($row['mobile']) ?>" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Update Data</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Hapus -->
<div class="modal fade" id="modalHapusUser<?= $row['id'] ?>" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Konfirmasi Hapus</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin ingin menghapus data mahasiswa: <br><strong><?= htmlspecialchars($row['name']); ?></strong>?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <a href="user data.php?action=delete_user&id=<?= $row['id'] ?>" class="btn btn-danger">Ya, Hapus</a>
      </div>
    </div>
  </div>
</div>
<?php endforeach; ?>

</body>
</html>
<?php Database::disconnect(); ?>
