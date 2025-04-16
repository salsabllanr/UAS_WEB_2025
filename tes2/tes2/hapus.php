<?php
session_start();

// Hanya izinkan role 'staff' untuk menghapus
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'staff') {
    echo "Akses ditolak!";
    exit;
}

// Ambil ID dari URL
if (isset($_GET['id'])) {
    $index = $_GET['id'];

    // Baca data dari file JSON
    $file = 'data/literasi.json';
    if (file_exists($file)) {
        $data = json_decode(file_get_contents($file), true);

        // Cek dan hapus data berdasarkan index
        if (isset($data[$index])) {
            unset($data[$index]);

            // Simpan ulang data ke file
            file_put_contents($file, json_encode(array_values($data), JSON_PRETTY_PRINT));
        }

        // Kembali ke staf.php
        header('Location: staff.php');
        exit;
    } else {
        echo "File data tidak ditemukan.";
    }
} else {
    echo "Index tidak diberikan.";
}
?>
