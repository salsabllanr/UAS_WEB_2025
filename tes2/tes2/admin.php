<?php
session_start();

// Cek apakah user adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Ambil data literasi
$dataFile = 'data/literasi.json';
$books = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : [];

// Filter hasil pencarian jika ada input pencarian
if (isset($_GET['q']) && $_GET['q'] !== '') {
    $keyword = strtolower(trim($_GET['q']));
    $books = array_filter($books, function($book) use ($keyword) {
        return strpos(strtolower($book['judul']), $keyword) !== false;
    });
}

// Simpan log aktivitas login ke file log.json
$logFile = 'data/log.json';
$logAktivitas = file_exists($logFile) ? json_decode(file_get_contents($logFile), true) : [];

// Ambil nilai dari session
$username = $_SESSION['username'] ?? 'Tidak diketahui';
$role = $_SESSION['role'] ?? 'Tidak diketahui';

// Tambahkan ke log
$logAktivitas[] = [
    'username' => $username,
    'waktu' => date('Y-m-d'),
    'role' => $role
];

file_put_contents($logFile, json_encode($logAktivitas, JSON_PRETTY_PRINT));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin - Literasi App</title>
    <link rel="stylesheet" href="tampilan/admin.css">
</head>
<body>
    <!-- Navbar -->
    <nav>
        <div class="container">
            <h1 class="logo">Literasi Admin</h1>
            <ul>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <main class="admin-page">
        <section class="admin-section">
            <h2 class="text-center">Daftar Semua Buku</h2>
            <form method="GET" class="search-form">
                <input type="text" name="q" placeholder="Cari judul buku..." value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
                <button type="submit">Cari</button>
            </form>
            <div class="book-cards">
                <?php if (empty($books)) : ?>
                    <p style="text-align: center;">Tidak ada buku yang cocok dengan pencarian.</p>
                <?php else : ?>
                    <?php foreach ($books as $b): ?>
                        <div class="book-card">
                            <img src="<?= htmlspecialchars($b['gambar']) ?>" alt="Cover">
                            <div class="book-info">
                                <h3><?= htmlspecialchars($b['judul']) ?></h3>
                                <p><?= htmlspecialchars($b['ringkasan']) ?></p>
                                <p><strong>Kategori:</strong> <?= htmlspecialchars($b['kategori']) ?></p>
                                <a href="<?= htmlspecialchars($b['link']) ?>" class="btn-read" target="_blank">Baca</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <section class="admin-section">
            <h2 class="text-center">Log Aktivitas User</h2>
            <table border="1" cellpadding="10" cellspacing="0" style="width:100%; background:#fff; border-collapse: collapse;">
                <thead style="background: #333; color: white;">
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Waktu Login</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($logAktivitas)) : ?>
                        <?php foreach ($logAktivitas as $index => $log) : ?>
                            <?php
                            $username = isset($log['username']) ? htmlspecialchars($log['username']) : 'Tidak diketahui';
                            $waktu = isset($log['waktu']) ? htmlspecialchars($log['waktu']) : 'Tidak diketahui';
                            $role = isset($log['role']) ? htmlspecialchars($log['role']) : 'Tidak diketahui';
                            ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= $username ?></td>
                                <td><?= $waktu ?></td>
                                <td><?= $role ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="10">Belum ada aktivitas login.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
