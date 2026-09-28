<?php
// admin/kategori.php — CRUD home + poli_m
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';
$active_menu = 'kategori';
$page_title = 'Kategori';
$page_sub = 'Kelola Home dan Poli (poli_m).';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $act = $_POST['act'] ?? '';
    if ($act === 'home_add') {
        $home = trim($_POST['home'] ?? '');
        if ($home !== '') {
            $s = mysqli_prepare($conn, 'INSERT INTO home(home, logo) VALUES(?, "folder.jpg")');
            mysqli_stmt_bind_param($s, 's', $home);
            if (mysqli_stmt_execute($s)) flash_set('success', "Home $home ditambah.");
            else flash_set('danger', 'Gagal: ' . mysqli_error($conn));
        }
    } elseif ($act === 'home_del') {
        $home = trim($_POST['home'] ?? '');
        // cegah jika masih dipakai poli_m
        $s = mysqli_prepare($conn, 'SELECT COUNT(*) FROM poli_m WHERE home=?');
        mysqli_stmt_bind_param($s, 's', $home);
        mysqli_stmt_execute($s);
        mysqli_stmt_bind_result($s, $c);
        mysqli_stmt_fetch($s);
        mysqli_stmt_close($s);
        if ($c > 0) {
            flash_set('danger', "Home $home masih dipakai $c poli. Nonaktifkan polisnya dulu.");
        } else {
            $d = mysqli_prepare($conn, 'DELETE FROM home WHERE home=?');
            mysqli_stmt_bind_param($d, 's', $home);
            mysqli_stmt_execute($d);
            flash_set('success', "Home $home dihapus.");
        }
    } elseif ($act === 'poli_add') {
        $poli = trim($_POST['poli'] ?? '');
        $home = trim($_POST['home'] ?? '');
        $active = isset($_POST['active']) ? 1 : 0;
        if ($poli !== '' && $home !== '') {
            $s = mysqli_prepare($conn, 'INSERT INTO poli_m(poli, logo, active, home) VALUES(?, "folder.jpg", ?, ?)');
            mysqli_stmt_bind_param($s, 'sis', $poli, $active, $home);
            if (mysqli_stmt_execute($s)) flash_set('success', "Poli $poli ditambah.");
            else flash_set('danger', 'Gagal: ' . mysqli_error($conn));
        } else {
            flash_set('danger', 'Poli dan home wajib diisi.');
        }
    } elseif ($act === 'poli_toggle') {
        $poli = trim($_POST['poli'] ?? '');
        $to = (int)($_POST['to'] ?? 0) ? 1 : 0;
        $s = mysqli_prepare($conn, 'UPDATE poli_m SET active=? WHERE poli=?');
        mysqli_stmt_bind_param($s, 'is', $to, $poli);
        mysqli_stmt_execute($s);
        flash_set('success', "Poli $poli -> " . ($to ? 'aktif' : 'nonaktif') . '.');
    } elseif ($act === 'poli_del') {
        $poli = trim($_POST['poli'] ?? '');
        $s = mysqli_prepare($conn, 'SELECT COUNT(*) FROM dokumen WHERE poli=?');
        mysqli_stmt_bind_param($s, 's', $poli);
        mysqli_stmt_execute($s);
        mysqli_stmt_bind_result($s, $c);
        mysqli_stmt_fetch($s);
        mysqli_stmt_close($s);
        if ($c > 0) {
            flash_set('danger', "Poli $poli masih punya $c dokumen. Nonaktifkan saja, jangan hapus.");
        } else {
            $d = mysqli_prepare($conn, 'DELETE FROM poli_m WHERE poli=?');
            mysqli_stmt_bind_param($d, 's', $poli);
            mysqli_stmt_execute($d);
            flash_set('success', "Poli $poli dihapus.");
        }
    }
    redirect('kategori');
}

$homes = mysqli_query($conn, 'SELECT home, logo FROM home ORDER BY home');
$polis = mysqli_query($conn, 'SELECT poli, home, active, (SELECT COUNT(*) FROM dokumen d WHERE d.poli=poli_m.poli) AS jml FROM poli_m ORDER BY home, poli');
require __DIR__ . '/inc/header.php';
?>
<h3>Kategori</h3>
<div class="row">
<div class="col-md-4">
  <h5>Home</h5>
  <table class="table table-sm table-bordered">
    <tr><th>Nama</th><th>Aksi</th></tr>
    <?php mysqli_data_seek($homes, 0); while ($r = mysqli_fetch_assoc($homes)): ?>
    <tr><td><?= e($r['home']) ?></td>
    <td><form method="POST" onsubmit="return confirm('Hapus home <?= e($r['home']) ?>?')"><input type="hidden" name="act" value="home_del"><input type="hidden" name="home" value="<?= e($r['home']) ?>"><button class="btn btn-sm btn-outline-danger">Hapus</button></form></td></tr>
    <?php endwhile; ?>
  </table>
  <form method="POST" class="form-inline">
    <input type="hidden" name="act" value="home_add">
    <input class="form-control form-control-sm mr-2" name="home" placeholder="Home baru" required>
    <button class="btn btn-sm btn-primary">Tambah</button>
  </form>
</div>
<div class="col-md-8">
  <h5>Poli (poli_m)</h5>
  <table class="table table-sm table-bordered">
    <tr><th>Poli</th><th>Home</th><th>Dok</th><th>Status</th><th>Aksi</th></tr>
    <?php while ($r = mysqli_fetch_assoc($polis)): ?>
    <tr>
      <td><?= e($r['poli']) ?></td><td><?= e($r['home']) ?></td><td><?= (int)$r['jml'] ?></td>
      <td><?= (int)$r['active']===1?'<span class="badge badge-success">aktif</span>':'<span class="badge badge-danger">nonaktif</span>' ?></td>
      <td class="text-nowrap">
        <form method="POST" style="display:inline"><input type="hidden" name="act" value="poli_toggle"><input type="hidden" name="poli" value="<?= e($r['poli']) ?>"><input type="hidden" name="to" value="<?= (int)$r['active']===1?0:1 ?>"><button class="btn btn-sm btn-warning"><?= (int)$r['active']===1?'Nonaktifkan':'Aktifkan' ?></button></form>
        <form method="POST" style="display:inline" onsubmit="return confirm('Hapus poli ini? Hanya bisa jika 0 dokumen.')"><input type="hidden" name="act" value="poli_del"><input type="hidden" name="poli" value="<?= e($r['poli']) ?>"><button class="btn btn-sm btn-outline-danger">Hapus</button></form>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>
  <h6>Tambah poli</h6>
  <form method="POST" class="form-row">
    <input type="hidden" name="act" value="poli_add">
    <div class="col"><input class="form-control form-control-sm" name="poli" placeholder="Nama poli" required></div>
    <div class="col"><select class="form-control form-control-sm" name="home" required><option value="">— home —</option><?php mysqli_data_seek($homes, 0); while ($h = mysqli_fetch_assoc($homes)): ?><option><?= e($h['home']) ?></option><?php endwhile; ?></select></div>
    <div class="col-auto"><label class="small"><input type="checkbox" name="active" checked> aktif</label></div>
    <div class="col-auto"><button class="btn btn-sm btn-primary">Tambah</button></div>
  </form>
</div>
</div>
<?php require __DIR__ . '/inc/footer.php'; ?>
