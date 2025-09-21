<?php
session_start();
if (isset($_GET['id'])) {
    $_SESSION['kelas_id'] = $_GET['id'];
    header("Location: dashboard_kelas.php?kelas_id=" . $_GET['id']);
    exit;
}
header("Location: dashboard.php");
exit;
