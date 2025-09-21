<?php
	$Write="<?php $" . "UIDresult=''; " . "echo $" . "UIDresult;" . " ?>";
	file_put_contents('UIDContainer.php',$Write);
?>

<!DOCTYPE html>
<html lang="en">
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset="utf-8">
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<script src="js/bootstrap.min.js"></script>
		<title>Beranda</title>
		<style>
			.img-wrapper {
				width: 100%;                 /* penuh lebar container */
				max-width: 600px;            /* opsional, batasi lebar max */
				margin: 0 auto;              /* center */
				aspect-ratio: 4 / 3;         /* rasio 3:4 */
				overflow: hidden;            /* crop gambar */
				border-radius: 10px;         /* opsional, biar sudut halus */
			}
			.img-wrapper img {
				width: 100%;
				height: 100%;
				object-fit: cover;           /* crop tengah */
				object-position: center;
				display: block;
			}
		</style>
	</head>
	
	<body>
		<div class="container">
			<h2 class="mt-3">Absensi Otomatis</h2>
			<?php include 'navbar.php'; ?>
			<br>
			<h3>Selamat Datang di Dashboard Sistem Absensi Otomatis</h3>
			<div class="img-wrapper">
				<img src="img/kerja.jpg" alt="Kerja">
			</div>
		</div>
	</body>
</html>