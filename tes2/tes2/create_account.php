<?php
session_start();
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $file = 'data/user.json';
    $users = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
    $username = $_POST['username'];
    $password = $_POST['password'];

    foreach ($users as $user) {
        if ($user['username'] === $username) {
            $message = "<span class='alert error'>Username sudah terdaftar. <a href='login.php'>Login sekarang</a></span>";
            break;
        }
    }

    if (empty($message)) {
        $users[] = ['username' => $username, 'password' => $password, 'role' => 'viewer'];
        file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));
        $message = "<span class='alert success'>Akun berhasil dibuat! <a href='login.php'>Login sekarang</a></span>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buat Akun</title>
    <link rel="stylesheet" href="tampilan/style.css">
</head>
<body class="login-container">
    <form method="POST" class="login-form">
        <h2>Buat Akun Baru</h2>
        <?php if (!empty($message)) echo $message; ?>
        <label>Username:</label>
        <input type="text" name="username" required>
        <br><br>
        <label>Password:</label>
        <input type="password" name="password" required>
        <br><br>
        <button type="submit">Daftar</button>
        <p>Sudah punya akun? <a href="login.php">Login sekarang</a></p>
    </form>
</body>
</html>
