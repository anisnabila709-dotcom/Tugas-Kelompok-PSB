<?php
require_once '../database.php';
require_once '../session.php';
ensure_logged_in();

$user_id = current_user_id();

// Query dengan filter status & pencarian keyword
$status = isset($_GET['status']) ? $_GET['status'] : 'all'; // all|done|undone
$q      = trim($_GET['q'] ?? '');
$sql    = "SELECT id, title, description, due_date, is_done, created_at, updated_at
           FROM tasks WHERE user_id = ?";
$params = [$user_id];
$types  = 'i';

if ($status === 'done') { $sql .= " AND is_done = 1"; }
elseif ($status === 'undone') { $sql .= " AND is_done = 0"; }

if ($q !== '') {
    $sql .= " AND (title LIKE ? OR description LIKE ?)";
    $like = "%$q%";
    $params[] = $like; $params[] = $like;
    $types .= 'ss';
}

$sql .= " ORDER BY due_date IS NULL, due_date ASC, id DESC";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"><title>Todo List</title>
  <link rel="stylesheet" href="/style.css">
</head>
<body>
<header class="topbar">
  <div>Halo, <?= htmlspecialchars($_SESSION['user']['name']) ?></div>
  <nav><a href="/auth/logout.php">Logout</a></nav>
</header>

<div class="container">
  <h1>Todo List</h1>

  <form action="/tasks/add.php" method="post" class="grid">
    <input type="text" name="title" placeholder="Judul tugas" required>
    <input type="text" name="description" placeholder="Deskripsi (opsional)">
    <input type="date" name="due_date" placeholder="Tanggal jatuh tempo">
    <button type="submit">Tambah</button>
  </form>

  <form method="get" class="filters">
    <select name="status">
      <option value="all"   <?= $status==='all'?'selected':'' ?>>Semua</option>
      <option value="undone"<?= $status==='undone'?'selected':'' ?>>Belum selesai</option>
      <option value="done"  <?= $status==='done'?'selected':'' ?>>Selesai</option>
    </select>
    <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Cari judul/deskripsi">
    <button type="submit">Terapkan</button>
  </form>

  <table>
    <thead>
      <tr>
        <th>Judul</th><th>Deskripsi</th><th>Jatuh Tempo</th><th>Status</th><th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result && $result->num_rows): while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['title']) ?></td>
          <td><?= htmlspecialchars($row['description'] ?? '') ?></td>
          <td><?= htmlspecialchars($row['due_date'] ?? '-') ?></td>
          <td><?= $row['is_done'] ? 'Selesai' : 'Belum' ?></td>
          <td class="actions">
            <a href="/tasks/toggle.php?id=<?= $row['id'] ?>"><?= $row['is_done'] ? 'Tandai belum' : 'Tandai selesai' ?></a>
            <a href="/tasks/edit.php?id=<?= $row['id'] ?>">Edit</a>
            <a href="/tasks/delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Hapus tugas ini?')">Hapus</a>
          </td>
        </tr>
      <?php endwhile; else: ?>
        <tr><td colspan="5" style="text-align:center">Belum ada tugas</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
</body>
</html>