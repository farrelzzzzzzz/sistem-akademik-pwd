<?php
session_start();

if(!isset($_SESSION['report'])){
  header("Location: index.php");
  exit;
}

$data = $_SESSION['report'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Report Akun Akademik</title>

<style>
  body{
    font-family: Arial, sans-serif;
    background:#f4f6ff;
  }

  .container{
    max-width:700px;
    margin:50px auto;
    background:#fff;
    padding:40px;
    border-radius:12px;
    box-shadow:0 10px 30px rgba(0,0,0,.15);
  }

  h2{
    text-align:center;
    margin-bottom:10px;
  }

  .subtitle{
    text-align:center;
    color:#555;
    margin-bottom:30px;
  }

  table{
    width:100%;
    border-collapse:collapse;
    margin-bottom:25px;
  }

  table td{
    padding:12px;
    border:1px solid #ddd;
  }

  table td:first-child{
    width:35%;
    font-weight:bold;
    background:#f0f3ff;
  }

  .alert{
    background:#fff3cd;
    padding:15px;
    border-radius:8px;
    font-size:14px;
    margin-bottom:25px;
  }

  .btn-group{
    display:flex;
    gap:15px;
    justify-content:center;
  }

  .btn{
    padding:12px 30px;
    border-radius:30px;
    border:none;
    cursor:pointer;
    font-size:16px;
  }

  .btn-print{
    background:#4b6fdc;
    color:#fff;
  }

  .btn-login{
    background:#fff;
    border:2px solid #4b6fdc;
    color:#4b6fdc;
  }

  footer{
    margin-top:30px;
    text-align:center;
    font-size:13px;
    color:#777;
  }

  /* ===== MODE CETAK ===== */
  @media print{
    body{
      background:white;
    }
    .btn-group{
      display:none;
    }
    .container{
      box-shadow:none;
      margin:0;
      border-radius:0;
    }
  }
</style>

</head>
<body>

<div class="container">

  <h2>LAPORAN DATA AKUN AKADEMIK</h2>
  <div class="subtitle">Sistem Informasi Akademik</div>

  <table>
    <tr>
      <td>Nama Lengkap</td>
      <td><?= htmlspecialchars($data['nama']) ?></td>
    </tr>
    <tr>
      <td>Role</td>
      <td><?= htmlspecialchars($data['role']) ?></td>
    </tr>
    <tr>
      <td>Username</td>
      <td><?= htmlspecialchars($data['username']) ?></td>
    </tr>
    <tr>
      <td>Password Awal</td>
      <td><?= htmlspecialchars($data['password']) ?></td>
    </tr>
    <tr>
      <td>Email</td>
      <td><?= htmlspecialchars($data['email']) ?></td>
    </tr>
  </table>

  <div class="alert">
    ⚠️ <b>PENTING:</b><br>
    Simpan laporan ini dengan baik. Password hanya ditampilkan satu kali.
    Setelah login pertama, disarankan segera mengganti password.
  </div>

  <div class="btn-group">
    <button class="btn btn-print" onclick="window.print()">Cetak / Simpan PDF</button>
    <button class="btn btn-login" onclick="location.href='login.php'">Lanjut Login</button>
  </div>

  <footer>
    Dicetak pada: <?= date('d-m-Y H:i') ?><br>
    © 2025 Sistem Akademik
  </footer>

</div>

</body>
</html>
