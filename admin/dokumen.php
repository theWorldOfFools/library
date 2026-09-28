<?php
// admin/dokumen.php — list + filter + paging + toggle aktif (soft delete saja)
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';
$active_menu = 'dokumen';
$page_title = 'Dokumen';
$page_sub = 'Kelola file PDF katalog. Hapus = nonaktifkan saja, file fisik tetap ada.';

$q = trim($_GET['q'] ?? '');
$f_home = trim($_GET['home'] ?? '');
$f_poli = trim($_GET['poli'] ?? '');
$f_status = trim($_GET['status'] ?? ''); // '' semua, '1' aktif, '0' nonaktif
$page = max(1, (int)($_GET['page'] ?? 1));
$per = 20;
$off = ($page - 1) * $per;

// dropdown data
$homes = [];
$hr = mysqli_query($conn, 'SELECT home FROM home ORDER BY home');
while ($r = mysqli_fetch_assoc($hr)) $homes[] = $r['home'];
$polis = []; // poli => home
$pr = mysqli_query($conn, 'SELECT poli, home, active FROM poli_m ORDER BY home, poli');
while ($r = mysqli_fetch_assoc($pr)) $polis[] = $r;

// build where (prepared)
$where = [];
$types = '';
$params = [];
if ($q !== '') { $where[] = 'd.namafile LIKE ?'; $types .= 's'; $params[] = "%$q%"; }
if ($f_poli !== '') { $where[] = 'd.poli = ?'; $types .= 's'; $params[] = $f_poli; }
if ($f_status === '1') { $where[] = 'd.active = 1'; }
elseif ($f_status === '0') { $where[] = '(d.active <> 1 OR d.active IS NULL)'; }
$join_home = '';
if ($f_home !== '') { $join_home = 'LEFT JOIN poli_m m ON m.poli = d.poli'; $where[] = 'm.home = ?'; $types .= 's'; $params[] = $f_home; }
$wsql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

$csql = "SELECT COUNT(*) FROM dokumen d $join_home $wsql";
$stmt = mysqli_prepare($conn, $csql);
if ($types) mysqli_stmt_bind_param($stmt, $types, ...$params);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $total);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);
$pages = max(1, (int)ceil($total / $per));

$lsql = "SELECT d.id, d.namafile, d.poli, d.jenis, d.active, d.jumlah_view FROM dokumen d $join_home $wsql ORDER BY d.id DESC LIMIT ? OFFSET ?";
$stmt = mysqli_prepare($conn, $lsql);
$types2 = $types . 'ii';
$params2 = array_merge($params, [$per, $off]);
mysqli_stmt_bind_param($stmt, $types2, ...$params2);
mysqli_stmt_execute($stmt);
$list = mysqli_stmt_get_result($stmt);

function qs($over = []) {
    $base = ['q' => $_GET['q'] ?? '', 'home' => $_GET['home'] ?? '', 'poli' => $_GET['poli'] ?? '', 'status' => $_GET['status'] ?? ''];
    return http_build_query(array_merge($base, $over));
}
require __DIR__ . '/inc/header.php';
?>
<div class="filter-bar">
<form class="form-row mb-0" method="GET" action="">
  <div class="col-md-3 mb-2"><input class="form-control" name="q" placeholder="Cari nama file…" value="<?= e($q) ?>"></div>
  <div class="col-md-2 mb-2">
    <select class="form-control" name="home"><option value="">Semua home</option>
    <?php foreach ($homes as $h): ?><option value="<?= e($h) ?>" <?= $f_home===$h?'selected':'' ?>><?= e($h) ?></option><?php endforeach; ?>
    </select>
  </div>
  <div class="col-md-3 mb-2">
    <select class="form-control" name="poli"><option value="">Semua poli</option>
    <?php foreach ($polis as $p): ?><option value="<?= e($p['poli']) ?>" <?= $f_poli===$p['poli']?'selected':'' ?>><?= e($p['home']) ?> / <?= e($p['poli']) ?><?= (int)$p['active']!==1?' (nonaktif)':'' ?></option><?php endforeach; ?>
    </select>
  </div>
  <div class="col-md-2 mb-2">
    <select class="form-control" name="status"><option value="">Semua status</option><option value="1" <?= $f_status==='1'?'selected':'' ?>>Aktif</option><option value="0" <?= $f_status==='0'?'selected':'' ?>>Nonaktif</option></select>
  </div>
  <div class="col-md-2 mb-2"><button class="btn btn-primary btn-block"><i class="fas fa-search mr-1"></i>Filter (<?= number_format($total) ?>)</button></div>
</form>
</div>
<div class="panel"><div class="table-responsive">
<table class="table table-sm table-admin">
<thead><tr><th>ID</th><th>Nama file</th><th>Poli</th><th>Jenis</th><th>View</th><th>Status</th><th>Aksi</th></tr></thead>
<tbody>
<?php while ($r = mysqli_fetch_assoc($list)): ?>
<tr>
  <td><?= (int)$r['id'] ?></td>
  <td><?= e($r['namafile']) ?></td>
  <td><span class="badge badge-info"><?= e($r['poli']) ?></span></td>
  <td><?= e($r['jenis']) ?></td>
  <td><?= (int)$r['jumlah_view'] ?></td>
  <td><?= ((int)$r['active']===1)?'<span class="badge badge-success">aktif</span>':'<span class="badge badge-danger">nonaktif</span>' ?></td>
  <td class="text-nowrap">
    <a class="btn btn-sm btn-secondary" href="dokumen-edit?id=<?= (int)$r['id'] ?>">Edit</a>
    <form method="POST" action="dokumen-toggle" style="display:inline" onsubmit="return confirm('Yakin ubah status? (hanya nonaktifkan, file fisik tidak dihapus)');">
      <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
      <input type="hidden" name="to" value="<?= ((int)$r['active']===1)?0:1 ?>">
      <button class="btn btn-sm <?= ((int)$r['active']===1)?'btn-warning':'btn-success' ?>"><?= ((int)$r['active']===1)?'Nonaktifkan':'Aktifkan' ?></button>
    </form>
  </td>
</tr>
<?php endwhile; ?>
</tbody></table>
</div></div>
<nav><ul class="pagination pagination-sm">
<?php for ($i = max(1,$page-3); $i <= min($pages,$page+3); $i++): ?>
  <li class="page-item <?= $i===$page?'active':'' ?>"><a class="page-link" href="?<?= qs(['page'=>$i]) ?>"><?= $i ?></a></li>
<?php endfor; ?>
</ul></nav>
<p class="text-muted small">Kebijakan hapus: hanya nonaktifkan (<code>active=0</code>). File fisik di <code>File_Dok_Lib/uploads/</code> tidak pernah dihapus otomatis.</p>
<?php require __DIR__ . '/inc/footer.php'; ?>
