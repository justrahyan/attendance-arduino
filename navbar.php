<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once 'database.php';
$pdo = Database::connect();

$kelas_aktif = isset($_SESSION['kelas_id']) ? $_SESSION['kelas_id'] : null;

Database::disconnect();
?>
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
        background-color: #e2ae3cff;
        width: 100%;
    }

    ul.topnav li {float: left;}

    ul.topnav li a {
        display: block;
        color: white;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
    }

    ul.topnav li a:hover:not(.active) {background-color: #947430ff;}

    ul.topnav li a.active {background-color: #947430ff;}

    ul.topnav li.right {float: right;}

    @media screen and (max-width: 600px) {
        ul.topnav li.right, 
        ul.topnav li {float: none;}
    }
    
    img {
        display: block;
        margin-left: auto;
        margin-right: auto;
    }
</style>

<ul class="topnav">
    <?php if (!$kelas_aktif): ?>
        <li><a class="<?= basename($_SERVER['PHP_SELF']) == 'home.php' ? 'active' : '' ?>" href="home.php">Home</a></li>
        <li><a class="<?= basename($_SERVER['PHP_SELF']) == 'daftar_kelas.php' ? 'active' : '' ?>" href="daftar_kelas.php">Daftar Kelas</a></li>
    <?php endif; ?>

    <?php if ($kelas_aktif): ?>
        <li><a class="<?= basename($_SERVER['PHP_SELF']) == 'dashboard_kelas.php' ? 'active' : '' ?>" href="dashboard_kelas.php?kelas_id=<?= $kelas_aktif ?>">Dashboard Kelas</a></li>
        <li><a class="<?= basename($_SERVER['PHP_SELF']) == 'daftar_hadir.php' ? 'active' : '' ?>" href="daftar_hadir.php">Daftar Hadir</a></li>
        <li><a class="<?= basename($_SERVER['PHP_SELF']) == 'rekap_hadir.php' ? 'active' : '' ?>" href="rekap_hadir.php">Rekap Daftar Hadir</a></li>
        <li><a class="<?= basename($_SERVER['PHP_SELF']) == 'user data.php' ? 'active' : '' ?>" href="user data.php">Data Mahasiswa</a></li>
        <li><a class="<?= basename($_SERVER['PHP_SELF']) == 'registration.php' ? 'active' : '' ?>" href="registration.php">Registrasi Kartu Mahasiswa</a></li>
        <li><a class="<?= basename($_SERVER['PHP_SELF']) == 'read tag.php' ? 'active' : '' ?>" href="read tag.php">Baca Kartu</a></li>
        <li class="right"><a href="keluar_kelas.php">Kembali</a></li>
    <?php endif; ?>
</ul>
