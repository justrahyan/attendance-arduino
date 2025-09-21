<?php
    require 'database.php';
    $id = null;
    if ( !empty($_GET['id'])) {
        $id = $_REQUEST['id'];
    }
     
    $pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "SELECT * FROM table_nodemcu_rfidrc522_mysql where id = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($id));
	$data = $q->fetch(PDO::FETCH_ASSOC);
	Database::disconnect();
?>

<!DOCTYPE html>
<html lang="en">
<html>
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
		}
		
		textarea {
			resize: none;
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
		</style>
		
		<title>Edit : NodeMCU V3 ESP8266 / ESP12E with MYSQL Database</title>
		
	</head>
	
	<body>

		<h2 align="center">NodeMCU V3 ESP8266 / ESP12E with MYSQL Database</h2>
		
		<div class="container">
     
			<div class="center" style="margin: 0 auto; width:495px; border-style: solid; border-color: #f2f2f2;">
				<div class="row">
					<h3 align="center">Edit User Data</h3>
					<p id="defaultGender" hidden><?php echo $data['gender'];?></p>
				</div>
		 
				<form class="form-horizontal" action="user data edit tb.php?id=<?php echo $id?>" method="post">
					<div class="control-group">
						<label class="control-label">ID</label>
						<div class="controls">
							<input class="form-control" disabled name="id" type="text" value="<?php echo $data['id'];?>" readonly>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">Name</label>
						<div class="controls">
							<input class="form-control" name="name" type="text" value="<?php echo $data['name'];?>" required>
						</div>
					</div>

					<!-- Tambahkan NIM -->
					<div class="control-group">
						<label class="control-label">NIM</label>
						<div class="controls">
							<input class="form-control" name="nim" type="text" value="<?php echo $data['nim'];?>" required>
						</div>
					</div>

					<!-- Tambahkan Class -->
					<div class="control-group">
						<label class="control-label">Kelas</label>
						<div class="controls">
							<input class="form-control" name="class" type="text" value="<?php echo $data['class'];?>" required>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">Gender</label>
						<div class="controls">
							<select class="form-control" name="gender" id="mySelect">
								<option value="Male">Male</option>
								<option value="Female">Female</option>
							</select>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">Email Address</label>
						<div class="controls">
							<input class="form-control" name="email" type="text" value="<?php echo $data['email'];?>" required>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">Mobile Number</label>
						<div class="controls">
							<input class="form-control" name="mobile" type="text" value="<?php echo $data['mobile'];?>" required>
						</div>
					</div>

					<div class="form-actions mt-4 d-flex flex-row gap-2 w-100">
						<button type="submit" class="btn btn-success w-100">Update</button>
						<a class="btn w-100" href="user data.php">Back</a>
					</div>
				</form>
			</div>               
		</div> <!-- /container -->	
		
		<script>
			var g = document.getElementById("defaultGender").innerHTML;
			if(g=="Male") {
				document.getElementById("mySelect").selectedIndex = "0";
			} else {
				document.getElementById("mySelect").selectedIndex = "1";
			}
		</script>
	</body>
</html>