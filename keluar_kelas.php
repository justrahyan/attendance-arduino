<?php
session_start();
unset($_SESSION['kelas_id']);
header("Location: home.php");
exit;
