<?php
session_start();
include '../koneksi.php';

/* ====================== AUTH DOSEN ====================== */
if(!isset($_SESSION['login']) || $_SESSION['role'] !== 'dosen'){
    header("Location: ../login.php");
    exit;
}

$id_dosen = $_SESSION['user_id'];

/* ====================== AMBIL MAHASISWA YANG DIAMPU DOSEN ====================== */
$mahasiswaQ = $conn->query("
    SELECT m.id AS id_mahasiswa, m.nim, m.nama
    FROM dosen_mahasiswa dm
    JOIN mahasiswa m ON dm.nim = m.nim
    WHERE dm.id_dosen='$id_dosen'
    ORDER BY m.nama ASC
");

/* ====================== SIMPAN NILAI ====================== */
if(isset($_POST['simpan'])){
    $id_mahasiswa = (int)$_POST['id_mahasiswa'];
    $id_matakuliah = (int)$_POST['id_matakuliah'];
    $nilai = $_POST['nilai'];

    // Konversi huruf ke bobot
    $bobot = 0;
    switch($nilai){
        case 'A': $bobot = 4; break;
        case 'B': $bobot = 3; break;
        case 'C': $bobot = 2; break;
        case 'D': $bobot = 1; break;
        case 'E': $bobot = 0; break;
    }

    // Cek apakah sudah ada nilai
    $cek = $conn->query("SELECT * FROM nilai WHERE id_mahasiswa=$id_mahasiswa AND id_matakuliah=$id_matakuliah");
    if($cek->num_rows > 0){
        $conn->query("UPDATE nilai SET nilai='$nilai', nilai_bobot=$bobot WHERE id_mahasiswa=$id_mahasiswa AND id_matakuliah=$id_matakuliah");
    } else {
        $conn->query("INSERT INTO nilai (id_mahasiswa, id_matakuliah, nilai, nilai_bobot) VALUES ($id_mahasiswa, $id_matakuliah, '$nilai', $bobot)");
    }

    header("Location: input_nilai.php"); // refresh halaman
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Input Nilai Mahasiswa</title>
    <link rel="stylesheet" href="../css/dosen_global.css">
</head>
<body>

<!-- NAVBAR -->
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



<div class="container">
    <h1>Input Nilai Mahasiswa</h1>

    <?php if($mahasiswaQ->num_rows > 0): ?>
        <?php while($mhs = $mahasiswaQ->fetch_assoc()): ?>
            <h2>Mahasiswa: <?= htmlspecialchars($mhs['nama']) ?> (<?= $mhs['nim'] ?>)</h2>

            <?php
            // Ambil KRS mahasiswa ini
            $krsQ = $conn->query("
                SELECT k.id AS krs_id,
                       m.id AS id_matkul,
                       m.kode_mk,
                       m.nama_mk,
                       m.sks,
                       m.kelas,
                       COALESCE(n.nilai, '-') AS nilai
                FROM krs k
                JOIN matakuliah m ON k.id_matakuliah = m.id
                LEFT JOIN nilai n ON n.id_mahasiswa = k.id_mahasiswa AND n.id_matakuliah = m.id
                WHERE k.id_mahasiswa = {$mhs['id_mahasiswa']}
                ORDER BY k.tanggal_pengisian DESC
            ");
            ?>

            <?php if($krsQ->num_rows > 0): ?>
                <table border="1" cellpadding="5" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode MK</th>
                            <th>Nama Mata Kuliah</th>
                            <th>SKS</th>
                            <th>Kelas</th>
                            <th>Nilai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; while($k = $krsQ->fetch_assoc()): ?>
                        <tr>
                            <form method="POST">
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($k['kode_mk']) ?></td>
                                <td><?= htmlspecialchars($k['nama_mk']) ?></td>
                                <td><?= $k['sks'] ?></td>
                                <td><?= htmlspecialchars($k['kelas']) ?></td>
                                <td>
                                    <select name="nilai" required>
                                        <option value="">--Pilih--</option>
                                        <?php
                                        $huruf = ['A','B','C','D','E'];
                                        foreach($huruf as $h){
                                            $selected = ($k['nilai'] == $h) ? 'selected' : '';
                                            echo "<option value='$h' $selected>$h</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="hidden" name="id_mahasiswa" value="<?= $mhs['id_mahasiswa'] ?>">
                                    <input type="hidden" name="id_matakuliah" value="<?= $k['id_matkul'] ?>">
                                    <button type="submit" name="simpan">Simpan</button>
                                </td>
                            </form>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="color:red;">Belum ada KRS untuk mahasiswa ini.</p>
            <?php endif; ?>

        <?php endwhile; ?>
    <?php else: ?>
        <p style="color:red;">Belum ada mahasiswa yang dibimbing.</p>
    <?php endif; ?>
</div>

</body>
</html>
