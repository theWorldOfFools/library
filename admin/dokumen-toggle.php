<?php
// admin/dokumen-toggle.php — POST only, soft on/off (tidak hapus fisik)
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('dokumen');
$id = (int)($_POST['id'] ?? 0);
$to = (int)($_POST['to'] ?? 0);
$to = $to === 1 ? 1 : 0;
if ($id <= 0) { flash_set('danger', 'ID tidak valid.'); redirect('dokumen'); }
$stmt = mysqli_prepare($conn, 'UPDATE dokumen SET active=? WHERE id=?');
mysqli_stmt_bind_param($stmt, 'ii', $to, $id);
if (mysqli_stmt_execute($stmt) && mysqli_stmt_affected_rows($stmt) >= 0) {
    flash_set('success', $to === 1 ? "Dokumen #$id diaktifkan." : "Dokumen #$id dinonaktifkan (file fisik tetap ada).");
} else {
    flash_set('danger', 'Gagal update: ' . mysqli_error($conn));
}
redirect('dokumen');
