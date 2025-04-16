<?php
require_once 'auth.php';
// Cek apakah pengguna sudah login, jika ya redirect ke halaman sesuai dengan role
if (is_logged_in()) {
    // Redirect berdasarkan role
    $role = get_user_role();
    if ($role === 'admin') {
        header("Location: admin.php"); // Halaman admin
    } elseif ($role === 'staff') {
        header("Location: staff.php"); // Halaman staf
    } else {
        header("Location: beranda.php"); // Halaman viewer
    }
    exit();
}

$error_message = '';

if (!empty($_POST)) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (login($username, $password)) {
        if (login($username, $password)) {
            // SET SESSION lebih dulu
            $_SESSION['username'] = $username;
            $_SESSION['role'] = get_user_role();  // pastikan get_user_role() return string: 'admin', 'staff', atau 'viewer'
            $role = $_SESSION['role'];
        
            // Baru catat log
            $logFile = 'data/log.json';
            $logAktivitas = file_exists($logFile) ? json_decode(file_get_contents($logFile), true) : [];
        
            $logAktivitas[] = [
                'username' => $_SESSION['username'],
                'waktu' => date('Y-m-d'),
                'role' => $_SESSION['role']
            ];
        
            file_put_contents($logFile, json_encode($logAktivitas, JSON_PRETTY_PRINT));
        
            // Redirect berdasarkan role
            if ($role === 'admin') {
                header("Location: admin.php");
            } elseif ($role === 'staff') {
                header("Location: staff.php");
            } else {
                header("Location: beranda.php");
            }
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="tampilan/style.css">
</head>
<body>
    <main>
        <div class="login-container">
            <div class="login-form">
                <h2>Login</h2>
                <?php if ($error_message): ?>
                    <p style="color: red;"><?php echo $error_message; ?></p>
                <?php endif; ?>
                <form method="POST" action="login.php">
                    <label>Username:</label><br>
                    <input type="text" name="username" id="username" required><br><br>
                    <label>Password:</label><br>
                    <input type="password" name="password" id="password" required><br><br>
                    <button type="submit">Login</button>
                </form>
                <p>Belum punya akun? <a href="create_account.php">Daftar di sini</a></p>
            </div>
        </div>
    </main>
</body>
</html>
