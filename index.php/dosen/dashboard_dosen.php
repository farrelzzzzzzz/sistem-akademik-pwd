<?php
session_start();
include '../koneksi.php';

/* Proteksi halaman */
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'dosen') {
    header("Location: ../login.php");
    exit;
}

/* Username dosen */
$username_dosen = $_SESSION['user_id'];

/* ===============================
   HITUNG JUMLAH MAHASISWA DOSEN
   =============================== */
$qMahasiswa = mysqli_query($conn, "
    SELECT COUNT(*) AS total 
    FROM dosen_mahasiswa 
    WHERE id_dosen = '$username_dosen'
");

$dataMahasiswa = mysqli_fetch_assoc($qMahasiswa);
$totalMahasiswa = $dataMahasiswa['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Dosen</title>

    <!-- PATH CSS TETAP -->
    <link rel="stylesheet" href="../css/dosen_dashboard.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="logo">Sistem Akademik</div>
    <div class="nav-menu">
        <a href="dashboard_dosen.php">Dashboard</a>
        <a href="mahasiswa.php">Data Mahasiswa</a>
        <a href="input_nilai.php">Input Nilai</a>
        <a href="logout.php">Logout</a>
    </div>
</nav>

<!-- HERO -->
<section class="hero-dosen">
    <div class="hero-overlay"></div>

    <div class="hero-content">
        <h1>Dashboard Dosen</h1>
        <p>Selamat datang, <?= htmlspecialchars($username_dosen) ?></p>
    </div>
</section>

<!-- STAT -->
<section class="stats-container">
    <div class="stat-card">
        <div class="stat-label">Jumlah Mahasiswa</div>
        <div class="stat-value"><?= $totalMahasiswa ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Status:</div>
        <div class="stat-value">Aktif</div>
    </div>
</section>

<!-- INFO -->
<section class="info-section">
    <div class="section-header">
        <div class="info-icon">i</div>
        <div class="section-title">Informasi Dosen</div>
    </div>

    <div class="info-content">
        <p><strong>ID Dosen:</strong> <?= htmlspecialchars($username_dosen) ?></p>
        <p><strong>Role:</strong> Dosen</p>
        <p><strong>Status:</strong> Aktif</p>
    </div>
</section>

<!-- FOOTER -->
<footer class="footer">
    <p>© <?= date('Y') ?> Sistem Akademik</p>
</footer>

</body>
</html>
