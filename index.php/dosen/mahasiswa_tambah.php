<?php
session_start();
include '../koneksi.php';

/* Proteksi role dosen */
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'dosen') {
    header("Location: ../login.php");
    exit;
}

$id_dosen = $_SESSION['user_id'];
$error = '';

/* Ambil mahasiswa yang BELUM dipilih dosen */
$mahasiswa = mysqli_query($conn, "
    SELECT * FROM mahasiswa
    WHERE nim NOT IN (
        SELECT nim FROM dosen_mahasiswa WHERE id_dosen='$id_dosen'
    )
    ORDER BY nama ASC
");

/* Simpan relasi */
if (isset($_POST['simpan'])) {
    $nim = mysqli_real_escape_string($conn, $_POST['nim']);

    if ($nim == '') {
        $error = "Mahasiswa harus dipilih!";
    } else {
        mysqli_query($conn, "
            INSERT INTO dosen_mahasiswa (id_dosen, nim)
            VALUES ('$id_dosen', '$nim')
        ");
        header("Location: mahasiswa.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Mahasiswa</title>

    <!-- CSS DOSEN -->
    <link rel="stylesheet" href="../css/dosen_global.css">

    <style>
        .form-wrapper {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,.1);
            max-width: 600px;
            margin: auto;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .form-group select {
            width: 100%;
            padding: 10px 12px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .form-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 25px;
        }

        .btn {
            padding: 10px 22px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: 600;
        }

        .btn-save {
            background-color: #4a7fc1;
            color: white;
        }

        .btn-back {
            background-color: #aaa;
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .error {
            background: #f8d7da;
            color: #842029;
            padding: 10px 15px;
            border-radius: 6px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<!-- NAVBAR (TIDAK DIUBAH) -->
<nav class="navbar">
    <div class="logo">Sistem Akademik</div>
    <div class="nav-menu">
        <a href="dashboard_dosen.php">Dashboard</a>
        <a href="mahasiswa.php">Data Mahasiswa</a>
        <a href="#">Input Nilai</a>
        <a href="#">Mata Kuliah</a>
        <a href="../logout.php">Logout</a>
    </div>
</nav>

<!-- HERO -->
<section class="hero">
    <div class="hero-content">
        <h1>Tambah Mahasiswa</h1>
        <p>Pilih mahasiswa dari database</p>
    </div>
</section>

<!-- FORM -->
<section class="info-section">
    <div class="form-wrapper">

        <?php if ($error != '') { ?>
            <div class="error"><?= $error ?></div>
        <?php } ?>

        <form method="POST">

            <div class="form-group">
                <label>Pilih Mahasiswa</label>
                <select name="nim" required>
                    <option value="">-- Pilih Mahasiswa --</option>
                    <?php while ($m = mysqli_fetch_assoc($mahasiswa)) { ?>
                        <option value="<?= $m['nim'] ?>">
                            <?= $m['nim'] ?> - <?= $m['nama'] ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-actions">
                <a href="mahasiswa.php" class="btn btn-back">Kembali</a>
                <button type="submit" name="simpan" class="btn btn-save">
                    Tambahkan
                </button>
            </div>

        </form>

    </div>
</section>

<!-- FOOTER -->
<footer class="footer">
    <p>© <?= date('Y') ?> Sistem Akademik</p>
</footer>

</body>
</html>
