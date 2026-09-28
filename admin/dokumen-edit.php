<?php
// admin/dokumen-edit.php — edit metadata + opsional ganti isi file (overwrite nama sama)
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';
$active_menu = 'dokumen';
$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
if ($id <= 0) { flash_set('danger', 'ID tidak valid.'); redirect('dokumen'); }

$stmt = mysqli_prepare($conn, 'SELECT id, namafile, logo, `no`, jenis, poli, active, jumlah_view FROM dokumen WHERE id=? LIMIT 1');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$doc = $res ? mysqli_fetch_assoc($res) : null;
mysqli_stmt_close($stmt);
if (!$doc) { flash_set('danger', 'Dokumen tidak ditemukan.'); redirect('dokumen'); }

$polis = [];
$pr = mysqli_query($conn, 'SELECT poli, home, active FROM poli_m ORDER BY home, poli');
while ($r = mysqli_fetch_assoc($pr)) $polis[] = $r;

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $poli = trim($_POST['poli'] ?? '');
    $jenis = trim($_POST['jenis'] ?? 'library');
    $active = isset($_POST['active']) && $_POST['active'] === '1' ? 1 : 0;
    if ($poli === '') {
        $err = 'Poli wajib diisi.';
    } else {
        $chk = mysqli_prepare($conn, 'SELECT COUNT(*) FROM poli_m WHERE poli=?');
        mysqli_stmt_bind_param($chk, 's', $poli);
        mysqli_stmt_execute($chk);
        mysqli_stmt_bind_result($chk, $c);
        mysqli_stmt_fetch($chk);
        mysqli_stmt_close($chk);
        if ($c == 0) {
            $err = 'Poli tidak dikenal.';
        } else {
            // opsional replace isi file (tetap pakai nama lama agar link frontend tidak putus)
            if (isset($_FILES['pdffile']) && ($_FILES['pdffile']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
                $f = $_FILES['pdffile'];
                if ($f['error'] !== UPLOAD_ERR_OK) {
                    $err = 'Ganti file gagal (kode ' . (int)$f['error'] . ').';
                } elseif ($f['size'] > ADMIN_MAX_UPLOAD) {
                    $err = 'File pengganti melebihi 100MB.';
                } else {
                    $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
                    if ($ext !== 'pdf') {
                        $err = 'File pengganti harus .pdf.';
                    } else {
                        $dest = ADMIN_UPLOAD_DIR . '/' . $doc['namafile'];
                        if (!move_uploaded_file($f['tmp_name'], $dest)) {
                            $err = 'Gagal menimpa file fisik (cek permission).';
                        } else {
                            @chmod($dest, 0644);
                        }
                    }
                }
            }
            if ($err === '') {
                $u = mysqli_prepare($conn, 'UPDATE dokumen SET poli=?, jenis=?, active=? WHERE id=?');
                mysqli_stmt_bind_param($u, 'ssii', $poli, $jenis, $active, $id);
                if (mysqli_stmt_execute($u)) {
                    flash_set('success', "Dokumen #$id diperbarui.");
                    redirect('dokumen');
                } else {
                    $err = 'Gagal update: ' . mysqli_error($conn);
                }
            }
        }
    }
    // refresh untuk tampil ulang
    $doc['poli'] = $_POST['poli'] ?? $doc['poli'];
    $doc['jenis'] = $_POST['jenis'] ?? $doc['jenis'];
    $doc['active'] = isset($_POST['active']) && $_POST['active']==='1' ? 1 : 0;
}
require __DIR__ . '/inc/header.php';
?>
<h3>Edit Dokumen #<?= (int)$doc['id'] ?></h3>
<p class="text-muted">File: <code><?= e($doc['namafile']) ?></code> · no: <?= e($doc['no']) ?> · view: <?= (int)$doc['jumlah_view'] ?></p>
<?php if ($err): ?><div class="alert alert-danger"><?= e($err) ?></div><?php endif; ?>
<form method="POST" enctype="multipart/form-data" action="">
  <input type="hidden" name="id" value="<?= (int)$doc['id'] ?>">
  <div class="form-group">
    <label>Poli *</label>
    <select class="form-control" name="poli" required>
      <?php foreach ($polis as $p): ?><option value="<?= e($p['poli']) ?>" <?= $doc['poli']===$p['poli']?'selected':'' ?>><?= e($p['home']) ?> / <?= e($p['poli']) ?></option><?php endforeach; ?>
    </select>
  </div>
  <div class="form-group">
    <label>Jenis</label>
    <input class="form-control" name="jenis" value="<?= e($doc['jenis']) ?>">
  </div>
  <div class="form-check mb-3">
    <input type="checkbox" class="form-check-input" id="active" name="active" value="1" <?= (int)$doc['active']===1?'checked':'' ?>>
    <label class="form-check-label" for="active">Aktif (tampil di katalog)</label>
  </div>
  <div class="form-group">
    <label>Ganti isi file (opsional, PDF ≤100MB, nama tetap <code><?= e($doc['namafile']) ?></code>)</label>
    <input type="file" class="form-control-file" name="pdffile" accept=".pdf,application/pdf">
  </div>
  <button class="btn btn-primary" type="submit">Simpan</button>
  <a class="btn btn-secondary" href="dokumen">Kembali</a>
</form>
<?php require __DIR__ . '/inc/footer.php'; ?>
