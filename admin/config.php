<?php
// admin/config.php — koneksi + helper bersama admin panel
// Reuse db.php existing (host 192.168.214.103, db e-book)
require_once __DIR__ . '/../db.php';

if (!isset($conn) || !$conn) {
    die('Koneksi database gagal: ' . mysqli_connect_error());
}
mysqli_set_charset($conn, 'utf8mb4');

define('ADMIN_UPLOAD_DIR', realpath(__DIR__ . '/../File_Dok_Lib/uploads') ?: (__DIR__ . '/../File_Dok_Lib/uploads'));
define('ADMIN_MAX_UPLOAD', 100 * 1024 * 1024); // 100MB sesuai permintaan

function e($s) {
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}
function flash_set($type, $msg) {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $_SESSION['_flash'] = ['type' => $type, 'msg' => $msg];
}
function flash_get() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $f = $_SESSION['_flash'] ?? null;
    unset($_SESSION['_flash']);
    return $f;
}
function redirect($url) {
    header('Location: ' . $url);
    exit();
}
// Role: 'admin' (akses penuh) vs 'supervisi' (semua kecuali kelola akun admin)
function admin_role() {
    return $_SESSION['admin_role'] ?? 'admin';
}
function is_full_admin() {
    return admin_role() === 'admin';
}
function require_full_admin($conn = null) {
    if (!is_full_admin()) {
        flash_set('danger', 'Hanya admin penuh yang boleh membuka menu Akun Admin.');
        redirect('index');
    }
}
