<?php
session_start();
$username = $_SESSION['username'] ?? '';
$file = "data/history_{$username}.json";
$histori = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
echo "<h2>Histori Bacaan</h2>";
if (count($histori) > 0) {
    echo "<ul>";
    foreach ($histori as $item) {
        echo "<li>{$item['judul']} - Dibaca pada {$item['waktu']}</li>";
    }
    echo "</ul>";
} else {
    echo "<p>Belum ada histori bacaan.</p>";
}
?>
