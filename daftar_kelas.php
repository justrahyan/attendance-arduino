<?php
session_start();
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
Database::disconnect();

// ambil pesan notifikasi kalau ada
$msg = $_SESSION['msg'] ?? null;
$msg_error = $_SESSION['msg_error'] ?? null;
unset($_SESSION['msg'], $_SESSION['msg_error']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Kelas</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
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

<?php if ($msg_error): ?>
<div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
  <div class="toast align-items-center bg-danger-subtle border-danger-subtle show" role="alert">
    <div class="d-flex">
      <div class="toast-body text-danger">
        <?= htmlspecialchars($msg_error) ?>
      </div>
      <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>
<?php endif; ?>

<div class="container">
    <h2 class="mt-3">Daftar Kelas</h2>
    <?php include 'navbar.php'; ?>

    <div class="text-start my-3">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambah">+ Tambah Kelas</button>
    </div>

    <table class="table table-bordered">
        <thead class="table-dark">
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
            <?php 
                $i = 1;
                foreach ($data_kelas as $row): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= htmlspecialchars($row['nama_kelas']); ?></td>
                    <td><?= htmlspecialchars($row['jam_masuk']); ?></td>
                    <td><?= htmlspecialchars($row['jam_selesai']); ?></td>
                    <td><?= htmlspecialchars($row['jumlah_mahasiswa']); ?></td>
                    <td>
                        <button 
                            class="btn btn-primary btn-sm" 
                            data-bs-toggle="modal" 
                            data-bs-target="#modalEdit<?= $row['id']; ?>">Edit</button>
                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalHapus<?= $row['id']; ?>">
                            Hapus
                        </button>
                        <a href="masuk_kelas.php?id=<?= $row['id']; ?>" class="btn btn-success btn-sm">Masuk Kelas</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal Tambah Kelas -->
<div class="modal fade" id="modalTambah" tabindex="-1">
  <div class="modal-dialog">
    <form method="post" action="tambah_kelas.php" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Kelas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
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
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Edit Kelas -->
<?php foreach ($data_kelas as $row): ?>
<div class="modal fade" id="modalEdit<?= $row['id']; ?>" tabindex="-1">
    <div class="modal-dialog">
      <form method="post" action="edit_kelas.php?id=<?= $row['id']; ?>" class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Kelas</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
              <label>Nama Kelas</label>
              <input type="text" name="nama_kelas" class="form-control" value="<?= htmlspecialchars($row['nama_kelas']); ?>" required>
          </div>
          <div class="mb-3">
              <label>Jam Masuk</label>
              <input type="time" name="jam_masuk" class="form-control" value="<?= $row['jam_masuk']; ?>" required>
          </div>
          <div class="mb-3">
              <label>Jam Selesai</label>
              <input type="time" name="jam_selesai" class="form-control" value="<?= $row['jam_selesai']; ?>" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Update</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        </div>
      </form>
    </div>
</div>
<?php endforeach; ?>

<!-- Modal Hapus Kelas -->
<?php foreach ($data_kelas as $row): ?>
<div class="modal fade" id="modalHapus<?= $row['id']; ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus kelas: <br>
                <strong><?= htmlspecialchars($row['nama_kelas']); ?></strong>?</p>
            </div>
            <div class="modal-footer">
                <a href="hapus_kelas.php?id=<?= $row['id']; ?>" class="btn btn-danger">Ya, Hapus</a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

</body>
</html>
