<?php
session_start();
include 'koneksi.php';

if(!isset($_SESSION['login']) || $_SESSION['role'] !== 'mahasiswa'){
    die("Akses ditolak");
}

$nim = $_SESSION['user_id'];

$qMhs = mysqli_query($conn, "SELECT id, nama, nim FROM mahasiswa WHERE nim='$nim'");
$mhs = mysqli_fetch_assoc($qMhs);
$id_mahasiswa = $mhs['id'];

$data = $conn->query("
    SELECT m.kode_mk, m.nama_mk, m.sks, m.kelas, n.nilai
    FROM krs k
    JOIN matakuliah m ON k.id_matakuliah = m.id
    LEFT JOIN nilai n ON n.id_mahasiswa = k.id_mahasiswa AND n.id_matakuliah = k.id_matakuliah
    WHERE k.id_mahasiswa = $id_mahasiswa
");

$ipkQ = $conn->query("
    SELECT ROUND(SUM(n.nilai_bobot*m.sks)/SUM(m.sks),2) AS ipk
    FROM nilai n
    JOIN matakuliah m ON n.id_matakuliah = m.id
    WHERE n.id_mahasiswa = $id_mahasiswa
");
$ipk = $ipkQ->fetch_assoc()['ipk'] ?? '0.00';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Laporan Nilai</title>
    <style>
        body{
            font-family: Arial, sans-serif;
            padding: 40px;
        }
        h2{
            text-align: center;
            margin-bottom: 20px;
        }
        table{
            width:100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td{
            border:1px solid #000;
            padding:8px;
            font-size:12px;
        }
        th{
            background:#eee;
        }
        .info{
            margin-bottom: 15px;
            font-size: 14px;
        }

        /* HILANGKAN SAAT CETAK */
        .no-print{
            margin-bottom:20px;
        }

        @media print{
            .no-print{ display:none; }
        }
    </style>
</head>
<body onload="window.print()">

<div class="no-print">
    <button onclick="window.print()">Cetak / Simpan PDF</button>
</div>

<h2>LAPORAN NILAI MAHASISWA</h2>

<div class="info">
    <strong>Nama:</strong> <?= $mhs['nama'] ?><br>
    <strong>NIM:</strong> <?= $mhs['nim'] ?><br>
    <strong>IPK:</strong> <?= $ipk ?>
</div>

<table>
    <tr>
        <th>No</th>
        <th>Kode MK</th>
        <th>Nama Mata Kuliah</th>
        <th>SKS</th>
        <th>Kelas</th>
        <th>Nilai</th>
    </tr>
    <?php $no=1; while($r = $data->fetch_assoc()): ?>
    <tr>
        <td><?= $no++ ?></td>
        <td><?= $r['kode_mk'] ?></td>
        <td><?= $r['nama_mk'] ?></td>
        <td><?= $r['sks'] ?></td>
        <td><?= $r['kelas'] ?></td>
        <td><?= $r['nilai'] ?? '-' ?></td>
    </tr>
    <?php endwhile; ?>
</table>

<p style="margin-top:40px; text-align:right;">
    Dicetak: <?= date('d-m-Y') ?>
</p>

</body>
</html>
