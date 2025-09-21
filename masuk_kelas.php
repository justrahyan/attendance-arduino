<?php
session_start();
if (isset($_GET['id'])) {
    $_SESSION['kelas_id'] = $_GET['id'];
}
header("Location: user data.php");
exit;
