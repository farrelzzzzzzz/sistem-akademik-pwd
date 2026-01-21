<?php
session_start();
include '../koneksi.php';

/* Proteksi dosen */
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'dosen') {
    header("Location: ../login.php");
    exit;
}

$id_dosen = $_SESSION['user_id'];

/* Ambil mahasiswa yang DIPILIH dosen */
$data = mysqli_query($conn, "
    SELECT m.*
    FROM dosen_mahasiswa dm
    JOIN mahasiswa m ON dm.nim = m.nim
    WHERE dm.id_dosen = '$id_dosen'
    ORDER BY m.nama ASC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Mahasiswa</title>

    <!-- CSS DOSEN (TIDAK DIUBAH) -->
    <link rel="stylesheet" href="../css/dosen_global.css">

    <style>
        .container {
            padding: 40px 5%;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,.08);
        }

        th, td {
            padding: 14px 16px;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #4a7fc1;
            color: white;
        }

        tr:hover {
            background: #f5f7fb;
        }

        .btn {
            padding: 8px 14px;
            border-radius: 6px;
            text-decoration: none;
            color: white;
            font-size: 14px;
            font-weight: 600;
        }

        .btn-add {
            background: #2ecc71;
        }

        .btn-del {
            background: #e74c3c;
        }

        .empty {
            text-align: center;
            padding: 30px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,.08);
        }
    </style>
</head>
<body>

<!-- NAVBAR (JANGAN DIUBAH) -->
<nav class="navbar">
    <div class="logo">Sistem Akademik</div>
    <div class="nav-menu">
        <a href="dashboard_dosen.php">Dashboard</a>
        <a href="mahasiswa.php" style="font-weight:600;color:#4a7fc1;">Data Mahasiswa</a>
        <a href="input_nilai.php">Input Nilai</a>
        <a href="logout.php">Logout</a>
    </div>
</nav>

<!-- KONTEN -->
<div class="container">

    <div class="page-header">
        <div class="page-title">Mahasiswa Bimbingan</div>
        <a href="mahasiswa_tambah.php" class="btn btn-add">+ Tambah Mahasiswa</a>
    </div>

    <?php if (mysqli_num_rows($data) > 0) { ?>
        <table>
            <tr>
                <th>No</th>
                <th>NIM</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>

            <?php $no = 1; while ($mhs = mysqli_fetch_assoc($data)) { ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($mhs['nim']) ?></td>
                <td><?= htmlspecialchars($mhs['nama']) ?></td>
                <td><?= htmlspecialchars($mhs['email']) ?></td>
                <td>
                    <a href="mahasiswa_hapus.php?nim=<?= $mhs['nim'] ?>"
                       class="btn btn-del"
                       onclick="return confirm('Hapus mahasiswa dari daftar Anda?')">
                       Hapus
                    </a>
                </td>
            </tr>
            <?php } ?>
        </table>
    <?php } else { ?>
        <div class="empty">
            <p>Belum ada mahasiswa yang ditambahkan.</p>
        </div>
    <?php } ?>

</div>

</body>
</html>
