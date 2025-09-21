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
	</head>
	
	<body>
		<div class="container">
			<h2 class="mt-3">Absensi Otomatis</h2>
			<?php include 'navbar.php'; ?>
			<br>
			<h3>Selamat Datang di Sistem Absensi Otomatis</h3>
			<img src="home ok ok.jpg" alt="" style="width:55%;">
		</div>
	</body>
</html>