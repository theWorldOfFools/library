<?php
// admin/index.php — dashboard
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';
$active_menu = 'dashboard';
$page_title = 'Dashboard';
$page_sub = 'Ringkasan katalog e-book (database e-book).';

function count_table($conn, $sql) {
    $r = mysqli_query($conn, $sql);
    $row = $r ? mysqli_fetch_row($r) : [0];
    return (int)$row[0];
}
$total_dok = count_table($conn, 'SELECT COUNT(*) FROM dokumen');
$total_aktif = count_table($conn, 'SELECT COUNT(*) FROM dokumen WHERE active=1');
$total_nonaktif = count_table($conn, 'SELECT COUNT(*) FROM dokumen WHERE active<>1 OR active IS NULL');
$total_home = count_table($conn, 'SELECT COUNT(*) FROM home');
$total_poli = count_table($conn, 'SELECT COUNT(*) FROM poli_m WHERE active=1');
$total_user = count_table($conn, 'SELECT COUNT(*) FROM user');
$total_view = count_table($conn, 'SELECT COALESCE(SUM(jumlah_view),0) FROM dokumen');

$top = mysqli_query($conn, 'SELECT namafile, poli, jumlah_view FROM dokumen ORDER BY jumlah_view DESC LIMIT 10');
$recent = mysqli_query($conn, 'SELECT id, namafile, poli, active FROM dokumen ORDER BY id DESC LIMIT 10');
$per_poli = mysqli_query($conn, 'SELECT poli, COUNT(*) c FROM dokumen GROUP BY poli ORDER BY c DESC LIMIT 15');

require __DIR__ . '/inc/header.php';
?>
<div class="stat-grid mb-3">
  <div class="stat"><div class="ic g1"><i class="fas fa-file-pdf"></i></div><div><h6>Total Dokumen</h6><p class="v"><?= $total_dok ?></p><small><span class="text-success"><?= $total_aktif ?> aktif</span> · <span class="text-danger"><?= $total_nonaktif ?> nonaktif</span></small></div></div>
  <div class="stat"><div class="ic g2"><i class="fas fa-eye"></i></div><div><h6>Total View</h6><p class="v"><?= number_format($total_view) ?></p><small>akumulasi jumlah_view</small></div></div>
  <div class="stat"><div class="ic g3"><i class="fas fa-home"></i></div><div><h6>Home</h6><p class="v"><?= $total_home ?></p><small>kategori utama</small></div></div>
  <div class="stat"><div class="ic g4"><i class="fas fa-folder"></i></div><div><h6>Poli aktif</h6><p class="v"><?= $total_poli ?></p><small>siap diisi dokumen</small></div></div>
  <div class="stat"><div class="ic g5"><i class="fas fa-users"></i></div><div><h6>User</h6><p class="v"><?= $total_user ?></p><small>akun katalog</small></div></div>
</div>
<div class="row">
  <div class="col-md-5"><div class="panel">
    <h5><i class="fas fa-fire"></i>Top 10 dilihat</h5>
    <div class="table-responsive"><table class="table table-sm table-admin">
      <thead><tr><th>File</th><th>Poli</th><th>View</th></tr></thead>
      <?php while ($r = mysqli_fetch_assoc($top)): ?>
      <tr><td><?= e(mb_strimwidth($r['namafile'],0,45,'…')) ?></td><td><span class="badge badge-info"><?= e($r['poli']) ?></span></td><td><b><?= (int)$r['jumlah_view'] ?></b></td></tr>
      <?php endwhile; ?>
    </table></div>
  </div></div>
  <div class="col-md-4"><div class="panel">
    <h5><i class="fas fa-clock"></i>Terbaru</h5>
    <div class="table-responsive"><table class="table table-sm table-admin">
      <thead><tr><th>File</th><th>Status</th></tr></thead>
      <?php while ($r = mysqli_fetch_assoc($recent)): ?>
      <tr><td><?= e(mb_strimwidth($r['namafile'],0,40,'…')) ?></td>
      <td><?= ((int)$r['active']===1)?'<span class="badge badge-success">aktif</span>':'<span class="badge badge-secondary">nonaktif</span>' ?></td></tr>
      <?php endwhile; ?>
    </table></div>
  </div></div>
  <div class="col-md-3"><div class="panel">
    <h5><i class="fas fa-chart-bar"></i>Per poli</h5>
    <div class="table-responsive"><table class="table table-sm table-admin">
      <thead><tr><th>Poli</th><th>Jml</th></tr></thead>
      <?php while ($r = mysqli_fetch_assoc($per_poli)): ?>
      <tr><td><?= e($r['poli']) ?></td><td><b><?= (int)$r['c'] ?></b></td></tr>
      <?php endwhile; ?>
    </table></div>
  </div></div>
</div>
<?php require __DIR__ . '/inc/footer.php'; ?>
