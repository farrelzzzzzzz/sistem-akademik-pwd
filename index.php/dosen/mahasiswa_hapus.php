<?php
session_start();
include '../koneksi.php';

/* Proteksi dosen */
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'dosen') {
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['nim'])) {
    header("Location: mahasiswa.php");
    exit;
}

$id_dosen = $_SESSION['user_id'];
$nim = mysqli_real_escape_string($conn, $_GET['nim']);

mysqli_query($conn, "
    DELETE FROM dosen_mahasiswa 
    WHERE id_dosen='$id_dosen' 
    AND nim='$nim'
");

header("Location: mahasiswa.php");
exit;
