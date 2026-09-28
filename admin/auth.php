<?php
// admin/auth.php — guard session untuk semua halaman admin (kecuali login)
session_start();
$timeout = 60 * 60; // 60 menit
if (isset($_SESSION['admin_last']) && (time() - $_SESSION['admin_last'] > $timeout)) {
    session_unset();
    session_destroy();
    header('Location: login');
    exit();
}
$_SESSION['admin_last'] = time();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login');
    exit();
}
// Pastikan role tersedia di session (untuk sesi lama sebelum ada role)
if (!isset($_SESSION['admin_role'])) {
    require_once __DIR__ . '/config.php';
    $s = mysqli_prepare($conn, 'SELECT role FROM admin WHERE id=? LIMIT 1');
    mysqli_stmt_bind_param($s, 'i', $_SESSION['admin_id']);
    mysqli_stmt_execute($s);
    mysqli_stmt_bind_result($s, $r);
    if (mysqli_stmt_fetch($s) && $r) {
        $_SESSION['admin_role'] = $r;
    } else {
        $_SESSION['admin_role'] = 'admin';
    }
    mysqli_stmt_close($s);
}
