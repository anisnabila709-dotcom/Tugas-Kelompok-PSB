<?php
session_start();
require_once("../database.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id   = $_SESSION["user_id"];
$user_name = $_SESSION["user_name"];

/* ADD TASK */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_task"])) {
    $title = trim($_POST["title"]);
    $desc  = trim($_POST["description"]);
    $due   = $_POST["due_date"];

    if ($title !== '') {
        $stmt = $conn->prepare(
            "INSERT INTO tasks (user_id, title, description, due_date, status)
             VALUES (?, ?, ?, ?, 'pending')"
        );
        $stmt->bind_param("isss", $user_id, $title, $desc, $due);
        $stmt->execute();
    }
    header("Location: index.php");
    exit;
}

/* TOGGLE */
if (isset($_GET["toggle"])) {
    $id = (int)$_GET["toggle"];
    $stmt = $conn->prepare(
        "UPDATE tasks SET status = IF(status='pending','completed','pending')
         WHERE id=? AND user_id=?"
    );
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    header("Location: index.php");
    exit;
}

/* DELETE */
if (isset($_GET["delete"])) {
    $id = (int)$_GET["delete"];
    $stmt = $conn->prepare(
        "DELETE FROM tasks WHERE id=? AND user_id=?"
    );
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    header("Location: index.php");
    exit;
}

/* FILTER & SEARCH */
$status = $_GET["status"] ?? "all";
$q      = trim($_GET["q"] ?? "");

$sql = "SELECT * FROM tasks WHERE user_id=?";
$params = [$user_id];
$types  = "i";

if ($status !== "all") {
    $sql .= " AND status=?";
    $params[] = $status;
    $types .= "s";
}
if ($q !== "") {
    $sql .= " AND (title LIKE ? OR description LIKE ?)";
    $like = "%$q%";
    $params[] = $like;
    $params[] = $like;
    $types .= "ss";
}
$sql .= " ORDER BY due_date ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Todo List</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: #f4f6f8;
}
.wrapper {
    max-width: 1200px;
    margin: 60px auto;
}
.card {
    border-radius: 16px;
    border: none;
}
.header {
    border-bottom: 1px solid #e5e7eb;
    padding-bottom: 16px;
    margin-bottom: 24px;
}
.search-input {
    width: 220px;
    border-radius: 999px;
    padding-left: 14px;
}
.filter-chip a {
    padding: 6px 14px;
    border-radius: 999px;
    text-decoration: none;
    font-size: 0.9rem;
    color: #6b7280;
    background: #f1f3f5;
}
.filter-chip a.active {
    background: #e3f2ed;
    color: #2f6f5f;
    font-weight: 500;
}
.table th {
    font-size: 0.85rem;
    color: #6b7280;
}
.task-done {
    text-decoration: line-through;
    color: #9aa0a6;
}
.badge-pending {
    background: #eef2f0;
    color: #3a6b55;
}
.badge-done {
    background: #e3f2ed;
    color: #2f6f5f;
}
.action-group {
    display: flex;
    gap: 6px;
}
</style>
</head>

<body>
<div class="wrapper">
<div class="card shadow-sm">
<div class="card-body px-5 py-4">

<!-- HEADER -->
<div class="header d-flex justify-content-between align-items-center">
    <div>
        <h4 class="mb-1">Todo List Student Planner</h4>
        <small class="text-muted">Halo, <?= htmlspecialchars($user_name) ?></small>
    </div>

    <div class="d-flex align-items-center gap-2">
        <form method="GET">
            <input type="text" name="q"
                   value="<?= htmlspecialchars($q) ?>"
                   class="form-control form-control-sm search-input"
                   placeholder="Cari tugas..."
                   onchange="this.form.submit()">
            <input type="hidden" name="status" value="<?= $status ?>">
        </form>
        <a href="../auth/logout.php" class="btn btn-outline-secondary btn-sm">Logout</a>
    </div>
</div>

<!-- ADD TASK -->
<form method="POST" class="row g-3 mb-4">
<input type="hidden" name="add_task">
<div class="col-md-4">
    <input type="text" name="title" class="form-control" placeholder="Judul tugas" required>
</div>
<div class="col-md-4">
    <input type="text" name="description" class="form-control" placeholder="Deskripsi">
</div>
<div class="col-md-3">
    <input type="date" name="due_date" class="form-control" required>
</div>
<div class="col-md-1 d-grid">
    <button class="btn btn-success">+</button>
</div>
</form>

<!-- FILTER STATUS -->
<div class="d-flex gap-2 mb-3 filter-chip">
<?php
$filters = ['all'=>'Semua','pending'=>'Pending','completed'=>'Selesai'];
foreach ($filters as $key=>$label):
?>
<a href="?status=<?= $key ?>&q=<?= urlencode($q) ?>"
   class="<?= $status===$key?'active':'' ?>">
   <?= $label ?>
</a>
<?php endforeach; ?>
</div>

<!-- TABLE -->
<table class="table align-middle">
<thead>
<tr>
    <th style="width:22%">Judul</th>
    <th style="width:28%">Deskripsi</th>
    <th style="width:15%">Deadline</th>
    <th style="width:15%">Status</th>
    <th style="width:20%">Aksi</th>
</tr>
</thead>
<tbody>

<?php if ($result->num_rows === 0): ?>
<tr><td colspan="5" class="text-center text-muted py-4">Tidak ada tugas</td></tr>
<?php endif; ?>

<?php while ($row = $result->fetch_assoc()): ?>
<tr>
<td class="<?= $row['status']==='completed'?'task-done':'' ?>">
    <?= htmlspecialchars($row['title']) ?>
</td>
<td><?= htmlspecialchars($row['description']) ?></td>
<td><?= htmlspecialchars($row['due_date']) ?></td>
<td>
    <?= $row['status']==='completed'
        ? '<span class="badge badge-done">Selesai</span>'
        : '<span class="badge badge-pending">Pending</span>' ?>
</td>
<td>
    <div class="action-group">
        <a href="?toggle=<?= $row['id'] ?>" class="btn btn-outline-success btn-sm">✓</a>
        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-outline-primary btn-sm">Edit</a>
        <a href="?delete=<?= $row['id'] ?>" class="btn btn-outline-danger btn-sm"
           onclick="return confirm('Hapus tugas ini?')">✕</a>
    </div>
</td>
</tr>
<?php endwhile; ?>

</tbody>
</table>

</div>
</div>
</div>
</body>
</html>

