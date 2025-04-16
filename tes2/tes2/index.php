<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Literasi Membaca</title>
    <link rel="stylesheet" href="tampilan/style.css">
</head>
<body>
    <!-- Navbar -->
    <nav>
        <div class="container">
            <h1 class="logo">Mari Literasi</h1>
            <ul>
                <?php if (isset($_SESSION['username'])): ?>
                    <!-- Menampilkan menu untuk pengguna yang sudah login -->
                    <li><a href="logout.php">Login</a></li>
                    <li><a href="create_account.php">Daftar</a></li>
                <?php else: ?>
                    <!-- Menampilkan menu untuk pengguna yang belum login -->
                    <li><a href="login.php">Login</a></li>
                    <li><a href="create_account.php">Buat Akun</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-text">
            <h2>Temukan Buku Favoritmu!</h2>
            <p>Jelajahi koleksi buku kami yang menarik dan temukan bacaan terbaru.</p>
            <?php if (!isset($_SESSION['username'])): ?>
                <a href="login.php" class="login-link">Login</a>
                
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>© 2025 Literasi App. All Rights Reserved.</p>
    </footer>
</body>
</html>
