<?php
session_start();

// Cek apakah user adalah staff
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'staff') {
    header("Location: login.php");
    exit();
}

$dataFile = 'data/literasi.json';

// Inisialisasi error & success message
$error_message = '';
$success_message = '';

// Inisialisasi kategori
$kategori = '';

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mengambil data input dan membersihkan input dari karakter berbahaya
    $judul = isset($_POST['judul']) ? htmlspecialchars($_POST['judul']) : '';
    $kategori = isset($_POST['kategori']) ? htmlspecialchars($_POST['kategori']) : '';
    $ringkasan = isset($_POST['ringkasan']) ? htmlspecialchars($_POST['ringkasan']) : '';
    $link = isset($_POST['link']) ? filter_var($_POST['link'], FILTER_VALIDATE_URL) : '';
    $gambar = isset($_POST['gambar']) ? filter_var($_POST['gambar'], FILTER_VALIDATE_URL) : '';

    // Validasi sederhana
    if ($judul && $kategori && $ringkasan && $link && $gambar) {
        $newData = [
            'judul' => $judul,
            'kategori' => $kategori,
            'ringkasan' => $ringkasan,
            'link' => $link,
            'gambar' => $gambar
        ];

        // Membaca dan menyimpan data ke dalam file JSON
        $data = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : [];
        $data[] = $newData;
        file_put_contents($dataFile, json_encode($data, JSON_PRETTY_PRINT));

        // Menyimpan pesan sukses dan redirect ke staff.php
        $_SESSION['success_message'] = "Bacaan berhasil ditambahkan!";
        header("Location: staff.php");
        exit();
    } else {
        $error_message = "Semua kolom harus diisi dan link gambar/link bacaan harus valid.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Bacaan - Staff</title>
    <link rel="stylesheet" href="tampilan/input.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container">
            <h1 class="logo">Input Bacaan</h1>
            <ul class="nav-links">
                <li><a href="staff.php">Kembali</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Form Section -->
    <main class="form_input-container">
        <div class="form_input-form">
            <h2>Form Input Bacaan</h2>

            <!-- Error and Success Alerts -->
            <?php if ($error_message): ?>
                <div class="alert alert-error"><?= $error_message ?></div>
            <?php endif; ?>

            <?php if ($success_message): ?>
                <div class="alert alert-success"><?= $success_message ?></div>
            <?php endif; ?>

            <!-- Input Form -->
            <form method="POST" action="">
                <label for="judul">Judul:</label>
                <input type="text" id="judul" name="judul" value="<?= isset($judul) ? $judul : '' ?>" required><br><br>

                <label for="kategori">Kategori:</label>
                <select name="kategori" id="kategori" required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="Komik" <?= $kategori === 'Komik' ? 'selected' : '' ?>>Komik</option>
                    <option value="Novel" <?= $kategori === 'Novel' ? 'selected' : '' ?>>Novel</option>
                    <option value="Cerita Rakyat" <?= $kategori === 'Cerita Rakyat' ? 'selected' : '' ?>>Cerita Rakyat</option>
                    <option value="Jurnal" <?= $kategori === 'Jurnal' ? 'selected' : '' ?>>Jurnal</option>
                </select><br><br>

                <label for="ringkasan">Ringkasan:</label><br>
                <textarea name="ringkasan" id="ringkasan" rows="10" cols="50" required><?= isset($ringkasan) ? $ringkasan : '' ?></textarea><br><br>

                <label for="link">Link Bacaan:</label>
                <input type="url" name="link" id="link" value="<?= isset($link) ? $link : '' ?>" required><br><br>

                <label for="gambar">Link Gambar (URL):</label>
                <input type="url" name="gambar" id="gambar" value="<?= isset($gambar) ? $gambar : '' ?>" required><br><br>

                <button type="submit">Simpan Bacaan</button>
            </form>
        </div>
    </main>
</body>
</html>
