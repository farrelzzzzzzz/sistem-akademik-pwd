<?php
session_start();
include 'koneksi.php';

/* ================== AUTH ================== */
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: login.php");
    exit;
}


$nim = $_SESSION['user_id'];

// Ambil id_mahasiswa dari tabel mahasiswa
$qMhs = mysqli_query($conn, "SELECT id, nama FROM mahasiswa WHERE nim='$nim'");
$mhs = mysqli_fetch_assoc($qMhs);

$id_mahasiswa = $mhs['id']; // <-- ini penting untuk query krs
$nama = $mhs['nama'] ?? 'Mahasiswa';


/* ================== DEFAULT (AMAN UNTUK MAHASISWA BARU) ================== */
$status   = 'AKTIF';
$ipk      = '0.00';
$sks      = '0';
$semester = '1';

/* ================== FUTURE-PROOF (NANTI AKTIF SENDIRI) ==================
   Jangan dihapus. Ini DISENGAJA disiapkan.
   Kalau tabelnya belum ada, query tidak dijalankan.
========================================================================== */

/* === TOTAL SKS (dari KRS) === */
/* === TOTAL SKS (dari KRS) === */
$cekKrs = mysqli_query($conn, "SHOW TABLES LIKE 'krs'");
if (mysqli_num_rows($cekKrs) > 0) {
    $qSks = mysqli_query($conn, "
        SELECT IFNULL(SUM(m.sks),0) AS total_sks
        FROM krs k
        JOIN matakuliah m ON k.id_matakuliah = m.id
        WHERE k.id_mahasiswa = '$id_mahasiswa'
    ");

    if ($qSks) {
        $d = mysqli_fetch_assoc($qSks);
        $sks = $d['total_sks'];
    }
}



/* === IPK (dari NILAI) === */
$cekNilai = mysqli_query($conn, "SHOW TABLES LIKE 'nilai'");
if (mysqli_num_rows($cekNilai) > 0) {
    $qIpk = mysqli_query($conn, "
    SELECT ROUND(SUM(n.nilai_bobot*m.sks)/SUM(m.sks),2) AS ipk
    FROM nilai n
    JOIN matakuliah m ON n.id_matakuliah = m.id
    JOIN mahasiswa ma ON n.id_mahasiswa = ma.id
    WHERE ma.nim='$nim'
");

    if ($qIpk) {
        $d = mysqli_fetch_assoc($qIpk);
        $ipk = $d['ipk'] ?? '0.00';
    }
}
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mahasiswa</title>
    <link rel="stylesheet" href="css/role_mahasiswa.css">
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="logo">
            <img src="https://storage.googleapis.com/tagjs-prod.appspot.com/v1/yw6YVzfcvO/dqwa66oo_expires_30_days.png" alt="Logo">
            <span>Portal Akademik
        </div>

        </div>
        <div class="nav-wrapper">
            <div class="nav-menu">
                <a href="mhs_role.php">Dashboard</a>
                <a href="matakuliah.php">Matakuliah</a>
                <a href="krs.php">Krs</a>
                <a href="#">Nilai</a>
            </div>
        </div>
        <div class="user-dropdown">
            <button class="user-btn" id="userBtn">
                <div class="user-icon">👤</div>
                <span><?= htmlspecialchars($nama) ?></span>
            </button>

            <div class="dropdown-menu" id="dropdownMenu">
                <a href="logout.php" class="logout-btn">Logout</a>

            </div>
        </div>

    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Selamat Datang</h1>
            <p><?= htmlspecialchars($nama) ?></p>
        </div>
    </section>

    <!-- Stats Cards -->
    <!-- Stats -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-label">Status Mahasiswa</div>
            <div class="stat-value"><?= $status ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-label">IP Komulatif</div>
            <div class="stat-value"><?= $ipk ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Jumlah SKS</div>
            <div class="stat-value"><?= $sks ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Semester</div>
            <div class="stat-value"><?= $semester ?></div>
        </div>
    </div>

    <!-- Information Section -->
    <section class="info-section">
        <div class="section-header">
            <div class="info-icon">i</div>
            <h2 class="section-title">Informasi Akademik</h2>
        </div>
        <div class="info-content">
            <p class="info-subtitle">Info SIMERU :</p>
            <ul>
                <li>Surat izin perkuliahan, mahasiswa juga wajib mengisi form online ini atau bisa akses link ini goo.gl/VHN6q1 Setelah registrasi online, silahkan datang ke Disduk untuk verifikasi dan mendapatkan stempel pada surat dan dedokumentasikan. Data harus sesuai dengan surat ijin dan ketentuan WR I tentang perkuliahan.</li>
                <li>Untuk menghindari antrian, manfaatkan loket bank untuk pembayaran SPP, BRI, BSM, BNI Syariah, Bukopin Bank, BPD DIY (tanpa membawa formulir atau menyebut mailai ATM)</li>
                <li>Perhatian khusus untuk mahasiswa(i), pastikan bahwa tidak ada kekurangan pembayaran semester sebelumnya (sajikan Rp. 0,-), karna ada kemungkinan masalah pada waktu pengecekan sidang atau dinyatakan tidak dapat dinyatakan ujian skripsi.</li>
            </ul>
            <p class="info-subtitle">Kontak Staff Keuangan :</p>
            <ul>
                <li>Jika ada permasalahan keuangan yang belum jelas, silakan menghubungi hotline WA bagian keuangan di WA 0856-0007-0737</li>
            </ul>
            <p class="info-subtitle">Kontak Person Bank :</p>
            <ul>
                <li>BRI : Bapak Brata (+62 878-3919-7536)</li>
                <li>BNI Syariah : Bapak Kerti (+62 821-3656-6768)</li>
                <li>BSM : Afri Yulianto (+62 819-6556-582)</li>
                <li>BPD : Bapak Prima (+62 811-259-060)</li>
            </ul>
            <p class="info-subtitle">Info Pengambilan KTM :</p>
            <ul>
                <li>Pengambilan KTM mahasiswa wajib mengisi form online melalui link suad.id/ambil_KTM</li>
                <li>Pengambilan KTM akan dinformasikan via whatsapp oleh petugas setelah pengisian form. Apabila belum ada konfirmasi dari petugas selama 2x24 jam, silahkan hub nomor +62 815-5347-3023</li>
            </ul>
        </div>
    </section>

    <!-- News Section -->
    <section class="news-section">
        <div class="section-header">
            <div class="info-icon">📰</div>
            <h2 class="section-title">Berita Acara</h2>
        </div>
        <div class="news-card">
            <div class="news-image">📢</div>
            <div class="news-content">
                <h3>PENDAFTARAN PELATIHAN SOFTSKILLS TAHAP 1 TAHUN 2025</h3>
                <p>Assalamu'alaikum wr. wb. Hai Dahlan Muda! Kami sampaikan untuk pendaftaran Pelatihan Soft Skills Tahap 1 Tahun 2025 dapat dilakukan mulai Hari : Senin s.d Ahad</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="social-icons">
            <span>Sosial Media:</span>
            <a href="#" class="social-icon">📱</a>
            <a href="#" class="social-icon">▶️</a>
            <a href="#" class="social-icon">📞</a>
            <a href="#" class="social-icon">✉️</a>
        </div>
        <div>Copyright @ 2025 Sistem Akademik</div>
    </footer>
</body>

<script>
    const userBtn = document.getElementById('userBtn');
    const dropdown = document.getElementById('dropdownMenu');

    userBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        dropdown.classList.toggle('show');
    });

    document.addEventListener('click', function() {
        dropdown.classList.remove('show');
    });
</script>


</html>