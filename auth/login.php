<?php
require_once '../database.php';
require_once '../session.php';

if (isset($_SESSION['user'])) {
    header('Location: /tasks/index.php');
    exit;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';

    if ($email === '' || $pass === '') {
        $errors[] = 'Email dan password wajib diisi.';
    } else {
        $stmt = $mysqli->prepare('SELECT id, name, email, password_hash FROM users WHERE email = ? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $res = $stmt->get_result();
        $user = $res->fetch_assoc();
        $stmt->close();

        if (!$user || !password_verify($pass, $user['password_hash'])) {
            $errors[] = 'Email atau password salah.';
        } else {
            $_SESSION['user'] = ['id' => (int)$user['id'], 'name' => $user['name'], 'email' => $user['email']];
            $_SESSION['last_activity'] = time();
            header('Location: /tasks/index.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"><title>Login</title>
  <link rel="stylesheet" href="/style.css">
</head>
<body>
<div class="auth-card">
  <h1>Login</h1>
  <?php if (isset($_GET['registered'])): ?><div class="success">Registrasi berhasil, silakan login.</div><?php endif; ?>
  <?php if (isset($_GET['timeout'])): ?><div class="warning">Sesi berakhir karena tidak aktif. Silakan login lagi.</div><?php endif; ?>
  <?php if ($errors): ?><div class="error"><?php foreach ($errors as $e) echo "<p>".htmlspecialchars($e)."</p>"; ?></div><?php endif; ?>
  <form method="post">
    <label>Email<input type="email" name="email" required></label>
    <label>Password<input type="password" name="password" required></label>
    <button type="submit">Masuk</button>
    <p>Belum punya akun? <a href="/auth/register.php">Daftar</a></p>
  </form>
</div>
</body>
</html>