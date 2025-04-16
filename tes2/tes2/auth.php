<?php
session_start();

// Fungsi untuk memuat data pengguna dari file JSON
function load_users() {
    $json_data = file_get_contents('data/user.json');
    return json_decode($json_data, true);
}

// Fungsi untuk login
function login($username, $password) {
    $usersFile = 'data/user.json';
    if (!file_exists($usersFile)) return false;

    $users = json_decode(file_get_contents($usersFile), true);
    foreach ($users as $user) {
        if ($user['username'] === $username && $user['password'] === $password) {
            // ✅ Set session di sini
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            return true;
        }
    }
    return false;
}


// Fungsi untuk logout
function logout() {
    session_unset();
    session_destroy();
}

// Fungsi untuk memeriksa apakah pengguna sudah login
function is_logged_in() {
    return isset($_SESSION['username']);
}

// Fungsi untuk mendapatkan role pengguna yang sedang login
function get_user_role() {
    return isset($_SESSION['role']) ? $_SESSION['role'] : null;
}

// Proses login
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (login($username, $password)) {
        // Catat log aktivitas
        $logFile = 'data/log.json';
        $logAktivitas = file_exists($logFile) ? json_decode(file_get_contents($logFile), true) : [];
    
        $logAktivitas[] = [
            'username' => $_SESSION['username'],
            'role' => $_SESSION['role'],
            'waktu' => date('Y-m-d')
        ];
    
        file_put_contents($logFile, json_encode($logAktivitas, JSON_PRETTY_PRINT));
    
        // Redirect sesuai role
        if ($_SESSION['role'] === 'admin') {
            header("Location: admin.php");
            exit();
        } elseif ($_SESSION['role'] === 'staff') {
            header("Location: staff.php");
            exit();
        } else {
            header("Location: beranda.php");
            exit();
        }
    }
}    
?>
