<?php
session_start();
require_once("../database.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION["user_id"];
$id = (int)($_GET["id"] ?? 0);

// Ambil data task
$stmt = $conn->prepare(
    "SELECT id, title, description, due_date, status
     FROM tasks WHERE id = ? AND user_id = ?"
);
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$task = $result->fetch_assoc();

if (!$task) {
    die("Tugas tidak ditemukan.");
}

// Update task
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"]);
    $desc  = trim($_POST["description"]);
    $due   = $_POST["due_date"];
    $status = isset($_POST["completed"]) ? "completed" : "pending";

    $stmt = $conn->prepare(
        "UPDATE tasks SET title=?, description=?, due_date=?, status=?
         WHERE id=? AND user_id=?"
    );
    $stmt->bind_param("ssssii", $title, $desc, $due, $status, $id, $user_id);
    $stmt->execute();

    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Tugas</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width:600px">
  <div class="card shadow-sm">
    <div class="card-body">
      <h4 class="mb-3">Edit Tugas</h4>

      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Judul</label>
          <input type="text" name="title" class="form-control"
                 value="<?= htmlspecialchars($task['title']) ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Deskripsi</label>
          <textarea name="description" class="form-control"><?= htmlspecialchars($task['description']) ?></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Deadline</label>
          <input type="date" name="due_date" class="form-control"
                 value="<?= htmlspecialchars($task['due_date']) ?>">
        </div>

        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" name="completed"
            <?= $task['status']==='completed'?'checked':'' ?>>
          <label class="form-check-label">Tandai selesai</label>
        </div>

        <div class="d-flex gap-2">
          <button class="btn btn-success">Simpan</button>
          <a href="index.php" class="btn btn-secondary">Batal</a>
        </div>
      </form>
    </div>
  </div>
</div>

</body>
</html>
