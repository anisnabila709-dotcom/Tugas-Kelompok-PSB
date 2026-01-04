<?php
include '../database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name  = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // Hash password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Insert ke database
    $stmt = $conn->prepare(
        "INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)"
    );
    $stmt->bind_param("sss", $name, $email, $password_hash);

    if ($stmt->execute()) {
        header("Location: login.php");
        exit;
    } else {
        $error = "Email sudah terdaftar.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Register - MyDashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">
  <div class="card shadow" style="width: 400px;">
    <div class="card-body">
      <h3 class="card-title text-center">Buat Akun Baru</h3>
      <p class="text-center text-muted">Silakan isi form untuk mendaftar</p>
<<<<<<< HEAD

      <?php if (isset($error)) : ?>
        <div class="alert alert-danger"><?= $error ?></div>
      <?php endif; ?>

      <form method="POST">
        <div class="mb-3">
          <input type="text" name="name" class="form-control" placeholder="Nama Lengkap" required>
        </div>
        <div class="mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email" required>
=======
      <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
      <form method="POST">
        <div class="mb-3">
          <input type="text" name="username" class="form-control" placeholder="Username" required>
>>>>>>> d5a1ac101ea3da817e661d9d7103b3db0808fc60
        </div>
        <div class="mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <button type="submit" class="btn btn-success w-100">Register</button>
      </form>

      <p class="mt-3 text-center">
        Sudah punya akun? <a href="login.php">Login</a>
      </p>
    </div>
  </div>
</body>
</html>
