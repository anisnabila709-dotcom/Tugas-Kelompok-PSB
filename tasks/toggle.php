<?php
require_once '../database.php';
require_once '../session.php';
ensure_logged_in();

$id = (int)($_GET['id'] ?? 0);
$user_id = current_user_id();
if ($id > 0) {
    // Ambil status saat ini
    $stmt = $mysqli->prepare('SELECT is_done FROM tasks WHERE id = ? AND user_id = ? LIMIT 1');
    $stmt->bind_param('ii', $id, $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $task = $res->fetch_assoc();
    $stmt->close();

    if ($task) {
        $new = $task['is_done'] ? 0 : 1;
        $stmt = $mysqli->prepare('UPDATE tasks SET is_done = ? WHERE id = ? AND user_id = ?');
        $stmt->bind_param('iii', $new, $id, $user_id);
        $stmt->execute();
        $stmt->close();
    }
}
header('Location: /tasks/index.php');
exit;