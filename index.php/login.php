<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login | Sistem Akademik</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: system-ui; }
    </style>
</head>

<body class="overflow-hidden">

<div class="min-h-screen flex justify-center items-center
    bg-[url('asset/bg_login.jpg')] bg-cover bg-center">

    <div class="w-[820px] max-w-[95%]
        bg-white/40 backdrop-blur-md
        rounded-[48px] px-20 py-16
        flex flex-col items-center"
        style="box-shadow:0 30px 50px rgba(0,0,0,.35)">

        <h1 class="text-4xl font-bold mb-10">LOGIN</h1>

        <!-- ERROR -->
        <?php if(isset($_SESSION['error'])): ?>
            <div class="w-full bg-red-200 text-red-700 px-6 py-4 rounded-xl mb-8">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form action="proses_login.php" method="POST" class="w-full">

            <input type="text" name="user_id"
                placeholder="Username"
                required
                class="w-full bg-white text-xl px-10 py-6 mb-8 rounded-full">

            <input type="password" name="password"
                placeholder="Password"
                required
                class="w-full bg-white text-xl px-10 py-6 mb-12 rounded-full">

            <button type="submit"
                class="w-full bg-[#3F6EDC] text-white text-2xl py-5 rounded-full mb-6">
                MASUK
            </button>

            <a href="index.php"
                class="block w-full text-center bg-white
                text-[#3F6EDC] text-2xl py-5 rounded-full">
                KEMBALI
            </a>
        </form>
    </div>
</div>

<footer class="absolute bottom-0 w-full bg-[#406CD0] text-white text-center py-4">
  © 2025 Sistem Akademik
</footer>

</body>
</html>
