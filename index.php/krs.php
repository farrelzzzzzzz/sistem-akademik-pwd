<?php
session_start();
include 'koneksi.php';

// ===== AUTH MAHASISWA =====
if(isset($_SESSION['id_mahasiswa'])){
    $id_mahasiswa = $_SESSION['id_mahasiswa'];
} else {
    // default: tampilkan semua atau redirect
    $id_mahasiswa = 0;
}


/* ================== IDENTITAS ================== */
$nim = $_SESSION['user_id'];

/* ================== DATA MAHASISWA (YANG SUDAH ADA) ================== */
$qMhs = mysqli_query($conn, "
    SELECT nama
    FROM mahasiswa
    WHERE nim = '$nim'
");
$mhs = mysqli_fetch_assoc($qMhs);

$nama = $mhs['nama'] ?? 'Mahasiswa';

// ambil nim dari session
$nim = $_SESSION['user_id']; // ini sesuai login kamu

// ambil id_mahasiswa berdasarkan nim
$qMhs = mysqli_query($conn, "SELECT id FROM mahasiswa WHERE nim='$nim'");
$mhs = mysqli_fetch_assoc($qMhs);

if(!$mhs){
    die("Mahasiswa dengan NIM $nim tidak ditemukan di database!");
}

$id_mahasiswa = $mhs['id']; // ini yang nanti dipakai INSERT



// ===== AMBIL DATA MATKUL =====
$matkulQ = $conn->query("SELECT * FROM matakuliah ORDER BY nama_mk ASC");

// ===== SIMPAN KRS =====
if (isset($_POST['simpan'])) {
    $id_matkul = (int)$_POST['matkul'];
    $semester = $conn->real_escape_string($_POST['semester']);
    $tahun_akademik = $conn->real_escape_string($_POST['tahun_akademik']);
    $tanggal = date('Y-m-d');

    // Insert KRS
    $conn->query("INSERT INTO krs (id_mahasiswa, id_matakuliah, semester, tahun_akademik, tanggal_pengisian)
                  VALUES ($id_mahasiswa, $id_matkul, '$semester', '$tahun_akademik', '$tanggal')");
    header("Location: krs.php"); // refresh
}

// ===== HAPUS KRS =====
if (isset($_GET['hapus'])) {
    $id_krs = (int)$_GET['hapus'];
    $conn->query("DELETE FROM krs WHERE id=$id_krs AND id_mahasiswa=$id_mahasiswa");
    header("Location: krs.php");
}

// ===== AMBIL DATA KRS MAHASISWA =====
$krsQ = $conn->query("
    SELECT k.id, k.semester, k.tahun_akademik, k.tanggal_pengisian,
           m.nama_mk, m.sks
    FROM krs k
    JOIN matakuliah m ON k.id_matakuliah = m.id
    WHERE k.id_mahasiswa = $id_mahasiswa
    ORDER BY k.tanggal_pengisian DESC
");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KRS Mahasiswa</title>
    <link rel="stylesheet" href="css/matakuliah.css">
</head>

<body>


    <!-- Navbar -->
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

        <h1>Pengisian KRS</h1>

        <div class="form-section">
            <div class="form-container">
                <form method="POST">
                    <div class="form-group">
                        <label>Mata Kuliah</label>
                        <select name="matkul" required>
                            <option value="">-- Pilih Mata Kuliah --</option>
                            <?php while ($mk = $matkulQ->fetch_assoc()): ?>
                                <option value="<?= $mk['id'] ?>"><?= $mk['nama_mk'] ?> (<?= $mk['sks'] ?> SKS)</option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Semester</label>
                        <input type="text" name="semester" placeholder="Contoh: 1, 2, 3..." required>
                    </div>

                    <div class="form-group">
                        <label>Tahun Akademik</label>
                        <select name="tahun_akademik" required>
                            <option value="">-- Pilih Tahun Akademik --</option>
                            <option value="2024/2025">2024/2025</option>
                            <option value="2025/2026">2025/2026</option>
                            <option value="2026/2027">2026/2027</option>
                        </select>
                    </div>

                    <button type="submit" name="simpan" class="btn-update">Simpan</button>
                </form>
            </div>
        </div>

        <div class="table-section">
            <h2>Daftar KRS Anda</h2>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Mata Kuliah</th>
                        <th>SKS</th>
                        <th>Semester</th>
                        <th>Tahun Akademik</th>
                        <th>Tanggal Pengisian</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    while ($k = $krsQ->fetch_assoc()): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($k['nama_mk']) ?></td>
                            <td><?= $k['sks'] ?></td>
                            <td><?= htmlspecialchars($k['semester']) ?></td>
                            <td><?= htmlspecialchars($k['tahun_akademik']) ?></td>
                            <td><?= $k['tanggal_pengisian'] ?></td>
                            <td>
                                <a href="krs.php?hapus=<?= $k['id'] ?>" class="btn-hapus" onclick="return confirm('Hapus KRS ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    <?php if ($krsQ->num_rows == 0): ?>
                        <tr>
                            <td colspan="7" style="text-align:center;">Belum ada KRS</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

        </div>

    </div>
    </div>

    <!-- Script Dropdown -->
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