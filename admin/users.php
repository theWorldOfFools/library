<?php
// admin/users.php — list read-only user katalog (400+ akun, login frontend MD5)
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';
$active_menu = 'users';
$page_title = 'User Katalog';
$page_sub = 'Read-only. Akun frontend (password MD5).';
$q = trim($_GET['q'] ?? '');
$page = max(1, (int)($_GET['page'] ?? 1));
$per = 20;
$off = ($page - 1) * $per;

if ($q !== '') {
    $like = "%$q%";
    $s = mysqli_prepare($conn, 'SELECT COUNT(*) FROM user WHERE username LIKE ?');
    mysqli_stmt_bind_param($s, 's', $like);
    mysqli_stmt_execute($s);
    mysqli_stmt_bind_result($s, $total);
    mysqli_stmt_fetch($s);
    mysqli_stmt_close($s);
    $s = mysqli_prepare($conn, 'SELECT username FROM user WHERE username LIKE ? ORDER BY username LIMIT ? OFFSET ?');
    mysqli_stmt_bind_param($s, 'sii', $like, $per, $off);
} else {
    $r = mysqli_query($conn, 'SELECT COUNT(*) FROM user');
    $total = (int)mysqli_fetch_row($r)[0];
    $s = mysqli_prepare($conn, 'SELECT username FROM user ORDER BY username LIMIT ? OFFSET ?');
    mysqli_stmt_bind_param($s, 'ii', $per, $off);
}
mysqli_stmt_execute($s);
$list = mysqli_stmt_get_result($s);
$pages = max(1, (int)ceil($total / $per));
require __DIR__ . '/inc/header.php';
?>
<h3>User Katalog <small class="text-muted">(<?= number_format($total) ?>)</small></h3>
<p class="text-muted small">Read-only. Akun ini dipakai <code>login.php</code> frontend (password MD5). Kelola akun admin di menu <a href="admins">Akun Admin</a>.</p>
<form class="form-inline mb-3" method="GET"><input class="form-control mr-2" name="q" placeholder="Cari username…" value="<?= e($q) ?>"><button class="btn btn-primary">Cari</button></form>
<table class="table table-sm table-striped table-bordered"><tr><th>#</th><th>Username</th></tr>
<?php $n = $off + 1; while ($r = mysqli_fetch_assoc($list)): ?>
<tr><td><?= $n++ ?></td><td><?= e($r['username']) ?></td></tr>
<?php endwhile; ?>
</table>
<nav><ul class="pagination pagination-sm">
<?php for ($i = max(1,$page-3); $i <= min($pages,$page+3); $i++): ?>
<li class="page-item <?= $i===$page?'active':'' ?>"><a class="page-link" href="?q=<?= urlencode($q) ?>&page=<?= $i ?>"><?= $i ?></a></li>
<?php endfor; ?>
</ul></nav>
<?php require __DIR__ . '/inc/footer.php'; ?>
