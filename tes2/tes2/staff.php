<?php
session_start();

// Cek apakah pengguna adalah staff
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'staff') {
    header("Location: login.php");
    exit();
}

$file = 'data/literasi.json';
$buku = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

// Hapus buku jika ada parameter delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    if (isset($buku[$id])) {
        unset($buku[$id]);
        file_put_contents($file, json_encode(array_values($buku), JSON_PRETTY_PRINT));
        header("Location: staf.php");
        exit();
    }
}

// Cek apakah ada pesan sukses
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']); // Menghapus pesan setelah ditampilkan
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Staff</title>
    <link rel="stylesheet" href="tampilan/staff.css">
</head>
<body>
    <nav>
        <div class="container">
            <h1 class="logo">Dashboard Staff</h1>
            <ul>
                <li><a href="form_input.php">Tambah Buku</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- Tempat untuk menampilkan pesan sukses -->
    <?php if (isset($success_message)): ?>
        <div class="alert alert-success"><?= $success_message ?></div>
    <?php endif; ?>

    <main class="book-list">
        <h2>Daftar Bacaan</h2>
        <div class="book-cards">
            <?php foreach ($buku as $index => $b) : ?>
                <div class="book-card">
                    <img src="<?php echo $b['gambar']; ?>" alt="Cover Buku">
                    <div class="book-info">
                        <h3><?php echo $b['judul']; ?></h3>
                        <p><?php echo $b['ringkasan']; ?></p>
                        <a href="<?php echo $b['link']; ?>" class="btn-read" target="_blank">Baca</a>
                        <br><br>
                        <a href="hapus.php?delete=<?php echo $index; ?>" class="btn-read" style="background-color:red" onclick="return confirm('Yakin ingin menghapus buku ini?')">Hapus</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>
