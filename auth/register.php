<?php
require_once '../database.php';
require_once '../session.php';

// Jika sudah login, redirect
if (isset($_SESSION['user'])) {
    header('Location: /tasks/index.php');
    exit;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if ($name === '' || $email === '' || $pass === '' || $confirm === '') {
        $errors[] = 'Semua kolom wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email tidak valid.';
    } elseif ($pass !== $confirm) {
        $errors[] = 'Konfirmasi password tidak cocok.';
    }

    if (!$errors) {
        // Cek email unik
        $stmt = $mysqli->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors[] = 'Email sudah terdaftar.';
        }
        $stmt->close();

        if (!$errors) {
            // Hash + salt implicit via password_hash
            $password_hash = password_hash($pass, PASSWORD_DEFAULT);
            $stmt = $mysqli->prepare('INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)');
            $stmt->bind_param('sss', $name, $email, $password_hash);
            $stmt->execute();
            $stmt->close();
            header('Location: /auth/login.php?registered=1');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"><title>Register</title>
  <link rel="stylesheet" href="/style.css">
</head>
<body>
<div class="auth-card">
  <h1>Register</h1>
  <?php if ($errors): ?><div class="error"><?php foreach ($errors as $e) echo "<p>".htmlspecialchars($e)."</p>"; ?></div><?php endif; ?>
  <form method="post">
    <label>Nama<input type="text" name="name" required></label>
    <label>Email<input type="email" name="email" required></label>
    <label>Password<input type="password" name="password" required minlength="6"></label>
    <label>Konfirmasi Password<input type="password" name="confirm" required minlength="6"></label>
    <button type="submit">Daftar</button>
    <p>Sudah punya akun? <a href="/auth/login.php">Login</a></p>
  </form>
</div>
</body>
</html>