<?php
session_start();
include 'koneksi.php';

// ===== AUTH DOSEN =====
if (!isset($_SESSION['login']) || $_SESSION['role'] != 'mahasiswa') {
    header("Location: index.php");
    exit;
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

// ===== TAMBAH MATKUL =====
if (isset($_POST['submit'])) {
    $kode_mk = $conn->real_escape_string($_POST['kode_mk']);
    $nama_mk = $conn->real_escape_string($_POST['nama_mk']);
    $sks     = (int)$_POST['sks'];
    $kelas   = $conn->real_escape_string($_POST['kelas']);
    $semester = $conn->real_escape_string($_POST['semester']);

    $conn->query("INSERT INTO matakuliah (kode_mk, nama_mk, sks, kelas, semester) 
                 VALUES ('$kode_mk', '$nama_mk', '$sks', '$kelas', '$semester')");
    header("Location: matakuliah.php"); // refresh halaman
}

// ===== HAPUS MATKUL =====
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    $conn->query("DELETE FROM matakuliah WHERE id=$id");
    header("Location: matakuliah.php");
}

// ===== UPDATE MATKUL =====
if (isset($_POST['update'])) {
    $id      = (int)$_POST['id'];
    $kode_mk = $conn->real_escape_string($_POST['kode_mk']);
    $nama_mk = $conn->real_escape_string($_POST['nama_mk']);
    $sks     = (int)$_POST['sks'];
    $kelas   = $conn->real_escape_string($_POST['kelas']);
    $semester = $conn->real_escape_string($_POST['semester']);

    $conn->query("UPDATE matakuliah 
                  SET kode_mk='$kode_mk', nama_mk='$nama_mk', sks='$sks', kelas='$kelas', semester='$semester'
                  WHERE id=$id");
    header("Location: matakuliah.php");
}

// ===== AMBIL SEMUA MATKUL =====
$result = $conn->query("SELECT * FROM matakuliah ORDER BY id ASC");

// ===== HITUNG TOTAL SKS =====
$total_sks = 0;
foreach ($result as $row) {
    $total_sks += $row['sks'];
}
$result->data_seek(0); // reset pointer
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Akademik - Matakuliah</title>
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

    <!-- Main Container -->
    <div class="container">
        <h1>Matakuliah</h1>

        <!-- Form Section -->
        <div class="form-section">
            <div class="form-container">
                <form method="POST">
                    <div class="form-group">
                        <label>Kode Matakuliah</label>
                        <input type="text" name="kode_mk" placeholder="IF001" required>
                    </div>

                    <div class="form-group">
                        <label>Nama Matakuliah</label>
                        <input type="text" name="nama_mk" placeholder="Statistika" required>
                    </div>

                    <div class="form-group">
                        <label>SKS</label>
                        <input type="number" name="sks" placeholder="Max.6" min="1" max="6" required>
                    </div>

                    <div class="form-group">
                        <label>Kelas</label>
                        <input type="text" name="kelas" placeholder="A" required>
                    </div>

                    <div class="form-group">
                        <label>Semester</label>
                        <select name="semester" required>
                            <option value="">Pilih Semester</option>
                            <option>Ganjil 2024/2025</option>
                            <option>Genap 2025/2026</option>
                            <option>Ganjil 2026/2027</option>
                            <option>Genap 2028/2029</option>
                        </select>
                    </div>

                    <button type="submit" name="submit" class="btn-update">Tambah Matakuliah</button>
                </form>
            </div>

            <div class="sks-card">
                <h3>Jumlah SKS</h3>
                <div class="number"><?= $total_sks ?></div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="table-section">
            <h2>Daftar Matakuliah</h2>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode MK</th>
                        <th>Nama MK</th>
                        <th>SKS</th>
                        <th>Kelas</th>
                        <th>Semester</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['kode_mk']) ?></td>
                            <td><?= htmlspecialchars($row['nama_mk']) ?></td>
                            <td><?= $row['sks'] ?></td>
                            <td><?= htmlspecialchars($row['kelas']) ?></td>
                            <td><?= htmlspecialchars($row['semester']) ?></td>
                            <td>
                                <!-- Tombol Update: pindah ke halaman update -->
                                <a href="update_matakuliah.php?id=<?= $row['id'] ?>">
                                    <button class="btn-update" type="button">Update</button>
                                </a>

                                <!-- Tombol Hapus: kirim parameter hapus -->
                                <a href="matakuliah.php?hapus=<?= $row['id'] ?>" onclick="return confirm('Yakin hapus matakuliah ini?')">
                                    <button class="btn-hapus" type="button">Hapus</button>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

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