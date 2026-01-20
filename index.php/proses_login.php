<?php
session_start();
include 'koneksi.php';

$user_id  = $_POST['user_id'];
$password = $_POST['password'];

$query = mysqli_query($conn,"
    SELECT * FROM users 
    WHERE username='$user_id' 
    AND status='aktif'
");

if(mysqli_num_rows($query) == 1){

    $user = mysqli_fetch_assoc($query);

    if(password_verify($password, $user['password_hash'])){

        $_SESSION['login']   = true;
        $_SESSION['user_id'] = $user['username'];
        $_SESSION['role']    = $user['role'];
    
        if($user['role'] === 'mahasiswa'){
            header("Location: mhs_role.php");
            exit;
        }
    
        if($user['role'] === 'dosen'){
            header("Location: dosen_role.php");
            exit;
        }
        
    } else {
        $_SESSION['error'] = "Password salah!";
        header("Location: login.php");
        exit;
    }

} else {
    $_SESSION['error'] = "Akun tidak ditemukan!";
    header("Location: login.php");
    exit;
}
