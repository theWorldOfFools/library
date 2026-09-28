<?php
// admin/admins.php — CRUD akun admin (tabel admin, password_hash)
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';
$active_menu = 'admins';
$page_title = 'Akun Admin';
$page_sub = 'Kelola akun admin (tabel admin, password_hash). Hanya admin penuh.';
require_full_admin($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $act = $_POST['act'] ?? '';
    $valid_roles = ['admin', 'supervisi'];
    if ($act === 'add') {
        $u = trim($_POST['username'] ?? '');
        $nama = trim($_POST['nama'] ?? '');
        $p1 = $_POST['password'] ?? '';
        $role = trim($_POST['role'] ?? 'admin');
        if (!in_array($role, $valid_roles, true)) $role = 'admin';
        if ($u === '' || $p1 === '') {
            flash_set('danger', 'Username dan password wajib diisi.');
        } else {
            $hash = password_hash($p1, PASSWORD_DEFAULT);
            $s = mysqli_prepare($conn, 'INSERT INTO admin(username, password_hash, nama, role) VALUES(?,?,?,?)');
            mysqli_stmt_bind_param($s, 'ssss', $u, $hash, $nama, $role);
            if (mysqli_stmt_execute($s)) flash_set('success', "Admin $u ($role) ditambah.");
            else flash_set('danger', 'Gagal: ' . mysqli_error($conn));
        }
    } elseif ($act === 'pass') {
        $id = (int)$_POST['id'];
        $p1 = $_POST['password'] ?? '';
        if ($id > 0 && $p1 !== '') {
            $hash = password_hash($p1, PASSWORD_DEFAULT);
            $s = mysqli_prepare($conn, 'UPDATE admin SET password_hash=? WHERE id=?');
            mysqli_stmt_bind_param($s, 'si', $hash, $id);
            mysqli_stmt_execute($s);
            flash_set('success', "Password admin #$id direset.");
        }
    } elseif ($act === 'del') {
        $id = (int)$_POST['id'];
        if ($id === (int)$_SESSION['admin_id']) {
            flash_set('danger', 'Tidak bisa hapus akun sendiri yang sedang login.');
        } elseif ($id > 0) {
            $s = mysqli_prepare($conn, 'DELETE FROM admin WHERE id=?');
            mysqli_stmt_bind_param($s, 'i', $id);
            mysqli_stmt_execute($s);
            flash_set('success', "Admin #$id dihapus.");
        }
    } elseif ($act === 'role') {
        $id = (int)$_POST['id'];
        $role = trim($_POST['role'] ?? 'admin');
        if (!in_array($role, ['admin', 'supervisi'], true)) $role = 'admin';
        if ($id === (int)$_SESSION['admin_id']) {
            flash_set('danger', 'Tidak bisa ubah role akun sendiri.');
        } elseif ($id > 0) {
            $s = mysqli_prepare($conn, 'UPDATE admin SET role=? WHERE id=?');
            mysqli_stmt_bind_param($s, 'si', $role, $id);
            mysqli_stmt_execute($s);
            flash_set('success', "Role admin #$id -> $role.");
        }
    }
    redirect('admins');
}
$list = mysqli_query($conn, 'SELECT id, username, nama, role, created_at FROM admin ORDER BY id');
require __DIR__ . '/inc/header.php';
?>
<h3>Akun Admin</h3>
<div class="panel"><div class="table-responsive"><table class="table table-sm table-admin">
<thead><tr><th>ID</th><th>Username</th><th>Nama</th><th>Role</th><th>Dibuat</th><th>Aksi</th></tr></thead>
<tbody>
<?php while ($r = mysqli_fetch_assoc($list)): ?>
<tr>
  <td><?= (int)$r['id'] ?></td><td><?= e($r['username']) ?></td><td><?= e($r['nama']) ?></td>
  <td><span class="badge badge-<?= ($r['role'] ?? 'admin')==='supervisi'?'info':'primary' ?>"><?= e($r['role'] ?? 'admin') ?></span></td>
  <td><?= e($r['created_at']) ?></td>
  <td class="text-nowrap">
    <form method="POST" style="display:inline" onsubmit="return confirm('Reset password admin <?= e($r['username']) ?>?')">
      <input type="hidden" name="act" value="pass"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
      <input type="password" name="password" placeholder="password baru" required class="form-control-sm" style="width:130px">
      <button class="btn btn-sm btn-warning">Reset</button>
    </form>
    <form method="POST" style="display:inline" onsubmit="return confirm('Ubah role admin <?= e($r['username']) ?>?')">
      <input type="hidden" name="act" value="role"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
      <select name="role" class="form-control-sm">
        <option value="admin" <?= ($r['role'] ?? 'admin')==='admin'?'selected':'' ?>>admin</option>
        <option value="supervisi" <?= ($r['role'] ?? '')==='supervisi'?'selected':'' ?>>supervisi</option>
      </select>
      <button class="btn btn-sm btn-info">Role</button>
    </form>
    <?php if ((int)$r['id'] !== (int)$_SESSION['admin_id']): ?>
    <form method="POST" style="display:inline" onsubmit="return confirm('Hapus admin ini?')"><input type="hidden" name="act" value="del"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>"><button class="btn btn-sm btn-outline-danger">Hapus</button></form>
    <?php endif; ?>
  </td>
</tr>
<?php endwhile; ?>
</tbody></table></div></div>
<h5>Tambah admin</h5>
<div class="panel"><form method="POST" class="form-row">
  <input type="hidden" name="act" value="add">
  <div class="col"><input class="form-control" name="username" placeholder="username" required></div>
  <div class="col"><input class="form-control" name="nama" placeholder="nama"></div>
  <div class="col"><input class="form-control" type="password" name="password" placeholder="password" required></div>
  <div class="col"><select class="form-control" name="role"><option value="admin">admin (penuh)</option><option value="supervisi">supervisi (tanpa Akun Admin)</option></select></div>
  <div class="col-auto"><button class="btn btn-primary">Tambah</button></div>
</form></div>
<div class="alert alert-warning mt-3 py-2 small">Akun default <code>admin / admin</code> sebaiknya diganti passwordnya setelah login pertama.</div>
<?php require __DIR__ . '/inc/footer.php'; ?>
