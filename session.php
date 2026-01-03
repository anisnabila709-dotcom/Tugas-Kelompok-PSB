<?php
if (session_status() === PHP_SESSION_NONE) {
    // Sesi cookie lebih aman
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'httponly' => true,
        'secure' => isset($_SERVER['HTTPS']), // aktifkan kalau HTTPS
        'samesite' => 'Lax'
    ]);
    session_start();
}

// Timeout inaktivitas (misal 30 menit)
const SESSION_TIMEOUT = 1800;

function ensure_logged_in(): void {
    if (!isset($_SESSION['user'])) {
        header('Location: /auth/login.php');
        exit;
    }
    // Reset timeout jika aktif
    $now = time();
    if (isset($_SESSION['last_activity']) && ($now - $_SESSION['last_activity']) > SESSION_TIMEOUT) {
        session_unset();
        session_destroy();
        header('Location: /auth/login.php?timeout=1');
        exit;
    }
    $_SESSION['last_activity'] = $now;
}

function current_user_id(): ?int {
    return $_SESSION['user']['id'] ?? null;
}