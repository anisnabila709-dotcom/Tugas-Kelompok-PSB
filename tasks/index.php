<?php
session_start();
require_once("../database.php");

if (!isset($_SESSION["user_id"])) {
    // simpan halaman asal sebelum redirect ke login
    $_SESSION["redirect_after_login"] = $_SERVER["REQUEST_URI"];
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION["user_id"];
$user_name = $_SESSION["user_name"];

// Tambah tugas (POST → Redirect → GET)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["title"])) {
    $title = $_POST["title"];
    $description = $_POST["description"];
    $due_date = $_POST["due_date"];

    $query = "INSERT INTO tasks (user_id, title, description, due_date, is_done) 
              VALUES ($user_id, '$title', '$description', '$due_date', 0)";
    mysqli_query($conn, $query);

    // Redirect agar tidak resubmit saat refresh
    header("Location: index.php");
    exit;
}

// Hapus tugas
if (isset($_GET["delete"])) {
    $id = $_GET["delete"];
    $query = "DELETE FROM tasks WHERE id=$id AND user_id=$user_id";
    mysqli_query($conn, $query);
    header("Location: index.php");
    exit;
}

// Toggle checklist
if (isset($_GET["toggle"])) {
    $id = $_GET["toggle"];
    $query = "UPDATE tasks SET is_done = IF(is_done=1,0,1) WHERE id=$id AND user_id=$user_id";
    mysqli_query($conn, $query);
    header("Location: index.php");
    exit;
}

// Ambil semua tugas
$sql = "SELECT * FROM tasks WHERE user_id = ? ORDER BY due_date ASC";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - MyDashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Halo, <?php echo $user_name; ?> 👋</h2>
    <h4>Daftar Tugas Kamu</h4>

    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Judul</th>
          <th>Deskripsi</th>
          <th>Deadline</th>
          <th>Selesai</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
          <tr>
            <td><?php echo htmlspecialchars($row["title"]); ?></td>
            <td><?php echo htmlspecialchars($row["description"]); ?></td>
            <td><?php echo htmlspecialchars($row["due_date"]); ?></td>
            <td>
              <a href="?toggle=<?php echo $row["id"]; ?>" class="btn btn-sm 
                <?php echo $row["is_done"] ? 'btn-success' : 'btn-outline-secondary'; ?>">
                <?php echo $row["is_done"] ? "✅" : "⬜"; ?>
              </a>
            </td>
            <td>
              <a class="btn btn-sm btn-outline-danger" href="?delete=<?php echo $row["id"]; ?>">❌ Hapus</a>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>

    <!-- Form tambah tugas -->
    <form method="POST" class="mb-3">
      <div class="mb-2">
        <input type="text" name="title" class="form-control" placeholder="Judul tugas..." required>
      </div>
      <div class="mb-2">
        <textarea name="description" class="form-control" placeholder="Deskripsi tugas..."></textarea>
      </div>
      <div class="mb-2">
        <input type="date" name="due_date" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary">Tambah</button>
    </form>

    <a href="../auth/logout.php" class="btn btn-danger">Logout</a>
</body>
</html>