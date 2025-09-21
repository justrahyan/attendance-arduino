<?php
session_start();

// tulis ulang UIDContainer
$Write="<?php $" . "UIDresult=''; " . "echo $" . "UIDresult;" . " ?>";
file_put_contents('UIDContainer.php',$Write);

require_once 'database.php';
$pdo = Database::connect();

// cek apakah ada kelas aktif
$nama_kelas = "Absensi Otomatis"; // default
if (isset($_SESSION['kelas_id'])) {
    $kelas_id = $_SESSION['kelas_id'];
    $stmt = $pdo->prepare("SELECT nama_kelas FROM table_kelas WHERE id = ?");
    $stmt->execute([$kelas_id]);
    $kelas = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($kelas) {
        $nama_kelas = $kelas['nama_kelas'];
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
    <style>
    html {
        font-family: Arial;
        display: inline-block;
        margin: 0px auto;
        text-align: center;
    }
    ul.topnav {
        list-style-type: none;
        margin: auto;
        padding: 0;
        overflow: hidden;
        background-color: #4CAF50;
        width: 70%;
    }
    ul.topnav li {float: left;}
    ul.topnav li a {
        display: block;
        color: white;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
    }
    ul.topnav li a:hover:not(.active) {background-color: #3e8e41;}
    ul.topnav li a.active {background-color: #333;}
    ul.topnav li.right {float: right;}
    @media screen and (max-width: 600px) {
        ul.topnav li.right, 
        ul.topnav li {float: none;}
    }
    .table {
        margin: auto;
        width: 90%; 
    }
    thead {
        color: #FFFFFF;
    }
    </style>
    <title>User Data</title>
</head>
<body>
    <div class="container">
        <h2 class="mt-3"><?= htmlspecialchars($nama_kelas) ?></h2>
        <?php include 'navbar.php'; ?>
        <br>
        <div class="container">
            <div class="row">
                <h3>Daftar Mahasiswa <?= htmlspecialchars($nama_kelas) ?></h3>
            </div>
            <div class="row">
                <table class="table table-striped table-bordered">
                <thead>
                    <tr bgcolor="#10a0c5" color="#FFFFFF">
                        <th>ID</th>
                        <th>Name</th>
                        <th>NIM</th>
                        <th>Jenis Kelamin</th>
                        <th>Email</th>
                        <th>No HP</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if (isset($_SESSION['kelas_id'])) {
					$kelas_id = $_SESSION['kelas_id'];

					$sql = "
						SELECT m.*
						FROM table_nodemcu_rfidrc522_mysql m
						INNER JOIN table_mahasiswa_kelas mk ON m.id = mk.mahasiswa_id
						WHERE mk.kelas_id = ?
						ORDER BY m.name ASC
					";
					$stmt = $pdo->prepare($sql);
					$stmt->execute([$kelas_id]);
					$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
				} else {
					// kalau belum pilih kelas, tampil kosong atau semua
					$sql = "SELECT * FROM table_nodemcu_rfidrc522_mysql ORDER BY name ASC";
					$result = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
				}

				foreach ($result as $row) {
					echo '<tr>';
					echo '<td>'. $row['id'] . '</td>';
					echo '<td>'. $row['name'] . '</td>';
					echo '<td>'. $row['nim'] . '</td>';
					echo '<td>'. $row['gender'] . '</td>';
					echo '<td>'. $row['email'] . '</td>';
					echo '<td>'. $row['mobile'] . '</td>';
					echo '<td>
							<a class="btn btn-success" href="user data edit page.php?id='.$row['id'].'">Edit</a>
							<a class="btn btn-danger" href="user data delete page.php?id='.$row['id'].'">Delete</a>
						</td>';
					echo '</tr>';
				}

                Database::disconnect();
                ?>
                </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
