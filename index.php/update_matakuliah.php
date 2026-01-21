<?php
session_start();
include 'koneksi.php';

// ===== AUTH DOSEN =====
if (!isset($_SESSION['login']) || $_SESSION['role'] != 'mahasiswa') {
    header("Location: index.php");
    exit;
}

$nama = $_SESSION['nama'] ?? 'Dosen';

// ===== AMBIL DATA MATKUL =====
if (!isset($_GET['id'])) {
    header("Location: matakuliah.php");
    exit;
}

$id = (int)$_GET['id'];
$q = $conn->query("SELECT * FROM matakuliah WHERE id=$id");
if ($q->num_rows == 0) {
    header("Location: matakuliah.php");
    exit;
}
$matkul = $q->fetch_assoc();

// ===== UPDATE MATKUL =====
if (isset($_POST['update'])) {
    $kode_mk = $conn->real_escape_string($_POST['kode_mk']);
    $nama_mk = $conn->real_escape_string($_POST['nama_mk']);
    $sks     = (int)$_POST['sks'];
    $kelas   = $conn->real_escape_string($_POST['kelas']);
    $semester= $conn->real_escape_string($_POST['semester']);

    $conn->query("UPDATE matakuliah 
                  SET kode_mk='$kode_mk', nama_mk='$nama_mk', sks='$sks', kelas='$kelas', semester='$semester'
                  WHERE id=$id");
    header("Location: matakuliah.php");
}

// ===== BATAL =====
if (isset($_POST['cancel'])) {
    header("Location: matakuliah.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Matakuliah</title>
    <link rel="stylesheet" href="css/update_matakuliah.css">
</head>
<body>
    <h1>Update Matakuliah</h1>

    <form method="POST">
        <label>Kode Matakuliah</label>
        <input type="text" name="kode_mk" value="<?= htmlspecialchars($matkul['kode_mk']) ?>" required>

        <label>Nama Matakuliah</label>
        <input type="text" name="nama_mk" value="<?= htmlspecialchars($matkul['nama_mk']) ?>" required>

        <label>SKS</label>
        <input type="number" name="sks" value="<?= $matkul['sks'] ?>" min="1" max="6" required>

        <label>Kelas</label>
        <input type="text" name="kelas" value="<?= htmlspecialchars($matkul['kelas']) ?>" required>

        <label>Semester</label>
        <input type="text" name="semester" value="<?= htmlspecialchars($matkul['semester']) ?>" required>

        <button type="submit" name="update">Update</button>
        <button type="submit" name="cancel">Kembali</button>
    </form>
</body>
</html>
