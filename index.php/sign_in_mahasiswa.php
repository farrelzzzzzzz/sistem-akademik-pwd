<?php
include 'koneksi.php';
session_start();

$info = "";

if(isset($_POST['daftar'])){
  $nama   = $_POST['nama'];
  $tgl    = $_POST['tgl_lahir'];
  $tahun  = $_POST['tahun'];
  $email  = $_POST['email'];

  // ambil urutan terakhir
  $q = mysqli_query($conn,"SELECT MAX(id) AS max FROM mahasiswa");
  $d = mysqli_fetch_assoc($q);
  $urut = $d['max'] + 1;

  // generate NIM
  $nim = substr($tahun,2,2).str_pad($urut,5,'0',STR_PAD_LEFT);
  $pass_plain = $nim."@".date('dmY',strtotime($tgl));
  $pass_hash  = password_hash($pass_plain,PASSWORD_DEFAULT);

  // simpan ke database
  mysqli_query($conn,"INSERT INTO mahasiswa VALUES(NULL,'$nim','$nama','$tgl','$tahun','$email')");
  mysqli_query($conn,"INSERT INTO users VALUES(NULL,'$nim','$pass_hash','mahasiswa','aktif')");

  // simpan ke session untuk report
  $_SESSION['report'] = [
    'role'     => 'Mahasiswa',
    'username' => $nim,
    'password' => $pass_plain,
    'nama'     => $nama,
    'email'    => $email
  ];

  header("Location: report_akun.php");
  exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Daftar Akademik</title>
  <link rel="stylesheet" href="css/sign_in.css">
</head>
<body>

<header>
<button class="pill outline" onclick="location.href='sign_in_mahasiswa.php'">
    Daftar Sebagai Mahasiswa
    <button class="pill outline" onclick="location.href='sign_in_dosen.php'">
    Daftar Sebagai Dosen
</header>

<div class="wrapper">
  <div class="left">
    <img src="asset/bg_signin.png">
  </div>

  <div class="right">
    <h1>Daftar Mahasiswa</h1>

    <?php if($info!=""){ ?>
      <div class="report"><?= $info ?></div>
    <?php } ?>

    <form method="post">
      <input type="hidden" name="role" value="mahasiswa">

      <label>Nama Lengkap</label>
      <input type="text" name="nama" placeholder="Nama Lengkap" required>

      <label>Tanggal Lahir</label>
      <input type="date" name="tgl_lahir" required>

      <label>Tahun Masuk</label>
      <select name="tahun" required>
        <option value="2023">2023</option>
        <option value="2024">2024</option>
      </select>

      <label>Email</label>
      <input type="email" name="email" placeholder="nama@example.com" required>

      <button class="btn-primary" name="daftar">Daftar</button>
      <button class="btn-outline" type="button" onclick="location.href='index.php'">Kembali</button>
    </form>
  </div>
</div>

<footer>
  Copyright © 2025 Sistem Akademik
</footer>

</body>
</html>
