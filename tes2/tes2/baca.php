<?php
session_start();
$username = $_SESSION['username'] ?? '';

function simpanHistori($judul) {
    global $username;
    if (!$username) return;
    $file = "data/history_{$username}.json";
    $histori = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
    foreach ($histori as $item) {
        if ($item['judul'] === $judul) return;
    }
    $histori[] = ['judul' => $judul, 'waktu' => date('Y-m-d H:i:s')];
    file_put_contents($file, json_encode($histori, JSON_PRETTY_PRINT));
}

if (isset($_GET['judul']) && isset($_GET['link'])) {
    simpanHistori($_GET['judul']);
    header("Location: " . $_GET['link']);
    exit;
}
?>
