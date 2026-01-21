<?php
session_start();
include 'koneksi.php';

// ===== AUTH MAHASISWA =====
if(!isset($_SESSION['login']) || $_SESSION['role'] !== 'mahasiswa'){
    header("Location: login.php");
    exit;
}

$nim = $_SESSION['user_id'];

// Ambil id_mahasiswa
$qMhs = mysqli_query($conn, "SELECT id, nama FROM mahasiswa WHERE nim='$nim'");
$mhs = mysqli_fetch_assoc($qMhs);
if(!$mhs) die("Mahasiswa dengan NIM $nim tidak ditemukan!");
$id_mahasiswa = $mhs['id'];
$nama = $mhs['nama'];

// Ambil daftar KRS mahasiswa
$krsQ = $conn->query("
    SELECT k.id AS krs_id, m.kode_mk, m.nama_mk, m.sks, m.kelas,
           n.nilai
    FROM krs k
    JOIN matakuliah m ON k.id_matakuliah = m.id
    LEFT JOIN nilai n ON n.id_mahasiswa = k.id_mahasiswa AND n.id_matakuliah = k.id_matakuliah
    WHERE k.id_mahasiswa = $id_mahasiswa
    ORDER BY k.tanggal_pengisian DESC
");

// Hitung IPK
$ipkQ = $conn->query("
    SELECT ROUND(SUM(n.nilai_bobot*m.sks)/SUM(m.sks),2) AS ipk
    FROM nilai n
    JOIN matakuliah m ON n.id_matakuliah = m.id
    WHERE n.id_mahasiswa = $id_mahasiswa
");
$ipkData = $ipkQ->fetch_assoc();
$ipk = $ipkData['ipk'] ?? '0.00';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Menu Nilai Mahasiswa</title>
    <link rel="stylesheet" href="css/matakuliah.css">
</head>
<body>

<nav class="navbar">
        <div class="logo">
            <img src="https://storage.googleapis.com/tagjs-prod.appspot.com/v1/yw6YVzfcvO/dqwa66oo_expires_30_days.png" alt="Logo">
            <span>Portal Akademik</span>
        </div>

        <div class="nav-wrapper">
            <div class="nav-menu">
                <a href="mhs_role.php">Dashboard</a>
                <a href="matakuliah.php">Matakuliah</a>
                <a href="krs.php">Krs</a>
                <a href="nilai.php">Nilai</a>
            </div>
        </div>

        <div class="user-dropdown">
            <button class="user-btn" id="userBtn" onclick="toggleDropdown()">
                <div class="user-icon">👤</div>
                <span><?= htmlspecialchars($nama) ?></span>
            </button>
            <div class="dropdown-menu" id="dropdownMenu">
                <a href="index.php" class="logout-btn">Logout</a>
            </div>
        </div>
    </nav>

<div class="container">
    <h1>Nilai Mahasiswa</h1>
    <p>IPK Saat Ini: <?= $ipk ?></p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode MK</th>
                <th>Nama Mata Kuliah</th>
                <th>SKS</th>
                <th>Kelas</th>
                <th>Nilai</th>
            </tr>
        </thead>
        <tbody>
            <?php $no=1; while($k = $krsQ->fetch_assoc()): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($k['kode_mk']) ?></td>
                    <td><?= htmlspecialchars($k['nama_mk']) ?></td>
                    <td><?= $k['sks'] ?></td>
                    <td><?= htmlspecialchars($k['kelas']) ?></td>
                    <td><?= $k['nilai'] ?? '-' ?></td>
                </tr>
            <?php endwhile; ?>
            <?php if($krsQ->num_rows == 0): ?>
                <tr>
                    <td colspan="6" style="text-align:center;">Belum ada KRS</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
        function toggleDropdown() {
            document.getElementById('dropdownMenu').classList.toggle('show');
        }

        window.onclick = function(event) {
            if (!event.target.matches('.user-btn') && !event.target.matches('.user-icon')) {
                var dropdown = document.getElementById('dropdownMenu');
                if (dropdown.classList.contains('show')) {
                    dropdown.classList.remove('show');
                }
            }
        }
    </script>

</body>
</html>
