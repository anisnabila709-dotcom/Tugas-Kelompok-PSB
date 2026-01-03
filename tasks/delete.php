<?php
require_once '../database.php';
require_once '../session.php';

ensure_logged_in();

$id = (int)($_GET['id'] ?? 0);
$user_id = current_user_id();

if ($id <= 0) {
    header('Location: /tasks/index.php');
    exit;
}

// Hapus task milik user yang login
$stmt = $mysqli->prepare(
    'DELETE FROM tasks WHERE id = ? AND user_id = ?'
);
$stmt->bind_param('ii', $id, $user_id);
$stmt->execute();
$stmt->close();

header('Location: /tasks/index.php');
exit;
