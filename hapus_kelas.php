<?php
require 'database.php';
$id = $_GET['id'];

$pdo = Database::connect();
$sql = "DELETE FROM table_kelas WHERE id=?";
$q = $pdo->prepare($sql);
$q->execute([$id]);
Database::disconnect();

header("Location: daftar_kelas.php");
