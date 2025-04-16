<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Ambil data buku
$dataFile = 'data/literasi.json';
$books = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : [];

// Proses pencarian
$search = $_GET['search'] ?? '';
$filteredBooks = array_filter($books, function ($book) use ($search) {
    return stripos($book['judul'], $search) !== false;
});
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Beranda - Literasi App</title>
    <link rel="stylesheet" href="tampilan/style.css">
</head>
<body>
    <!-- Navbar -->
    <nav>
        <div class="container">
            <h1 class="logo">Literasi App</h1>
            <ul>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero -->
    <section class="hero">
        <div class="hero-text">
            <h2>Selamat Datang, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>
            <p>Temukan buku favoritmu dan mulai membaca hari ini.</p>
        </div>
    </section>

    <!-- Pencarian -->
    <section class="search-section" style="text-align: center; margin-top: 30px;">
        <form method="GET" action="beranda.php">
            <input type="text" name="search" placeholder="Cari buku berdasarkan judul..." value="<?= htmlspecialchars($search) ?>" style="padding: 10px; width: 300px;">
            <button type="submit" class="btn-read">Cari</button>
        </form>
    </section>

    <!-- Daftar Buku -->
    <main class="book-list">
        <h2>Daftar Buku</h2>
        <div class="book-cards">
            <?php if (count($filteredBooks) > 0): ?>
                <?php foreach ($filteredBooks as $b): ?>
                    <div class="book-card">
                        <img src="<?= htmlspecialchars($b['gambar']) ?>" alt="Cover Buku">
                        <div class="book-info">
                            <h3><?= htmlspecialchars($b['judul']) ?></h3>
                            <p><?= htmlspecialchars($b['ringkasan']) ?></p>
                            <a href="<?= htmlspecialchars($b['link']) ?>" class="btn-read" target="_blank">Baca Sekarang</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Tidak ada buku ditemukan.</p>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <p>© 2025 Literasi App. All Rights Reserved.</p>
    </footer>
</body>
</html>
