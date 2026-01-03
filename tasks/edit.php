<?php
require_once '../database.php';
require_once '../session.php';
ensure_logged_in();

$id = (int)($_GET['id'] ?? 0);

// Ambil data
$stmt = $mysqli->prepare('SELECT id, title, description, due_date, is_done FROM tasks WHERE id = ? AND user_id = ? LIMIT 1');
$user_id = current_user_id();
$stmt->bind_param('ii', $id, $user_id);
$stmt->execute();
$res = $stmt->get_result();
$task = $res->fetch_assoc();
$stmt->close();

if (!$task) { die('Tugas tidak ditemukan atau tidak berhak.'); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $desc  = trim($_POST['description'] ?? '');
    $due   = trim($_POST['due_date'] ?? '');
    $done  = isset($_POST['is_done']) ? 1 : 0;

    if ($title !== '') {
        $stmt = $mysqli->prepare('UPDATE tasks SET title=?, description=?, due_date=?, is_done=? WHERE id=? AND user_id=?');
        $stmt->bind_param('sssiii', $title, $desc, $due === '' ? null : $due, $done, $id, $user_id);
        $stmt->execute();
        $stmt->close();
        header('Location: /tasks/index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"><title>Edit Tugas</title>
  <link rel="stylesheet" href="/style.css">
</head>
<body>
<div class="container">
  <h1>Edit Tugas</h1>
  <form method="post" class="grid">
    <label>Judul<input type="text" name="title" value="<?= htmlspecialchars($task['title']) ?>" required></label>
    <label>Deskripsi<textarea name="description" rows="4"><?= htmlspecialchars($task['description'] ?? '') ?></textarea></label>
    <label>Jatuh Tempo<input type="date" name="due_date" value="<?= htmlspecialchars($task['due_date'] ?? '') ?>"></label>
    <label class="checkbox"><input type="checkbox" name="is_done" <?= $task['is_done'] ? 'checked' : '' ?>> Tandai selesai</label>
    <div class="form-actions">
      <button type="submit">Simpan</button>
      <a href="/tasks/index.php">Batal</a>
    </div>
  </form>
</div>
</body>
</html>