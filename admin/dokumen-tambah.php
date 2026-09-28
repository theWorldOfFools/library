<?php
// admin/dokumen-tambah.php — form + upload PDF max 100MB ke File_Dok_Lib/uploads/
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';
$active_menu = 'dokumen-tambah';
$page_title = 'Upload Baru';
$page_sub = 'File tersimpan di File_Dok_Lib/uploads/. Maks 100MB, hanya PDF.';

function sanitize_filename($name) {
    $name = basename($name);
    $name = preg_replace('/[^A-Za-z0-9._\- ]+/', '_', $name);
    $name = trim($name, '._ ');
    if ($name === '') $name = 'dokumen.pdf';
    if (strtolower(substr($name, -4)) !== '.pdf') $name .= '.pdf';
    return $name;
}

// data dropdown
$homes = [];
$hr = mysqli_query($conn, 'SELECT home FROM home ORDER BY home');
while ($r = mysqli_fetch_assoc($hr)) $homes[] = $r['home'];
$polis = [];
$pr = mysqli_query($conn, 'SELECT poli, home, active FROM poli_m ORDER BY home, poli');
while ($r = mysqli_fetch_assoc($pr)) $polis[] = $r;
$jenis_list = [];
$jr = mysqli_query($conn, 'SELECT DISTINCT jenis FROM dokumen WHERE jenis IS NOT NULL AND jenis<>"" ORDER BY jenis');
while ($r = mysqli_fetch_assoc($jr)) $jenis_list[] = $r['jenis'];

$err = '';
$old = ['poli' => '', 'jenis' => 'library', 'active' => '1', 'home' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old['home'] = trim($_POST['home'] ?? '');
    $old['poli'] = trim($_POST['poli'] ?? '');
    $old['jenis'] = trim($_POST['jenis'] ?? 'library');
    $old['active'] = isset($_POST['active']) && $_POST['active'] === '1' ? '1' : '0';
    $active = (int)$old['active'];

    if ($old['poli'] === '') {
        $err = 'Poli wajib dipilih.';
    } elseif (!isset($_FILES['pdffile']) || $_FILES['pdffile']['error'] === UPLOAD_ERR_NO_FILE) {
        $err = 'File PDF wajib diupload.';
    } else {
        $f = $_FILES['pdffile'];
        if ($f['error'] !== UPLOAD_ERR_OK) {
            $err = 'Upload gagal (kode ' . (int)$f['error'] . '). Pastikan ukuran ≤ 100MB dan post_max/upload_max memadai.';
        } elseif ($f['size'] > ADMIN_MAX_UPLOAD) {
            $err = 'File melebihi 100MB.';
        } else {
            $safe = sanitize_filename($f['name']);
            $ext = strtolower(pathinfo($safe, PATHINFO_EXTENSION));
            if ($ext !== 'pdf') {
                $err = 'Hanya file .pdf yang diizinkan.';
            } else {
                // cek mime
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mime = $finfo->file($f['tmp_name']);
                if (!in_array($mime, ['application/pdf', 'application/x-pdf', 'application/octet-stream'], true)) {
                    // tetap tolak kecuali jelas pdf; octet-stream diizinkan karena browser kadang begitu
                    if (strpos((string)$mime, 'pdf') === false && $mime !== 'application/octet-stream') {
                        $err = 'Tipe file tidak valid (' . $mime . '). Upload PDF saja.';
                    }
                }
            }
            if ($err === '') {
                // validasi poli ada di poli_m
                $chk = mysqli_prepare($conn, 'SELECT COUNT(*) FROM poli_m WHERE poli=?');
                mysqli_stmt_bind_param($chk, 's', $old['poli']);
                mysqli_stmt_execute($chk);
                mysqli_stmt_bind_result($chk, $c);
                mysqli_stmt_fetch($chk);
                mysqli_stmt_close($chk);
                if ($c == 0) {
                    $err = 'Poli tidak dikenal. Tambahkan dulu di menu Kategori.';
                } else {
                    // hindari overwrite: tambah suffix
                    $dest = ADMIN_UPLOAD_DIR . '/' . $safe;
                    $base = pathinfo($safe, PATHINFO_FILENAME);
                    $i = 1;
                    while (file_exists($dest)) {
                        $safe = $base . '_' . $i . '.pdf';
                        $dest = ADMIN_UPLOAD_DIR . '/' . $safe;
                        if (++$i > 500) break;
                    }
                    if (!move_uploaded_file($f['tmp_name'], $dest)) {
                        $err = 'Gagal menyimpan file ke uploads/ (cek permission www-data).';
                    } else {
                        @chmod($dest, 0644);
                        // no = MAX(no)+1 untuk kompatibilitas search.php lama
                        $mr = mysqli_query($conn, 'SELECT COALESCE(MAX(no),0)+1 AS n FROM dokumen');
                        $mrow = mysqli_fetch_assoc($mr);
                        $no = (int)$mrow['n'];
                        $logo = 'icon.png';
                        $jenis = $old['jenis'] !== '' ? $old['jenis'] : 'library';
                        $stmt = mysqli_prepare($conn, 'INSERT INTO dokumen(namafile, logo, `no`, jenis, poli, active, jumlah_view) VALUES(?,?,?,?,?,?,0)');
                        mysqli_stmt_bind_param($stmt, 'ssissi', $safe, $logo, $no, $jenis, $old['poli'], $active);
                        if (mysqli_stmt_execute($stmt)) {
                            flash_set('success', "Berhasil upload $safe ke poli {$old['poli']}.");
                            redirect('dokumen?q=' . urlencode($safe));
                        } else {
                            @unlink($dest);
                            $err = 'Gagal insert DB: ' . mysqli_error($conn);
                        }
                    }
                }
            }
        }
    }
}
require __DIR__ . '/inc/header.php';
?>
<?php if ($err): ?><div class="alert alert-danger" style="border-radius:12px"><?= e($err) ?></div><?php endif; ?>
<div class="panel"><form method="POST" enctype="multipart/form-data" action="">
  <div class="form-row">
    <div class="form-group col-md-4">
      <label>Home (filter)</label>
      <select class="form-control" id="f_home" name="home">
        <option value="">— Semua —</option>
        <?php foreach ($homes as $h): ?><option value="<?= e($h) ?>" <?= $old['home']===$h?'selected':'' ?>><?= e($h) ?></option><?php endforeach; ?>
      </select>
    </div>
    <div class="form-group col-md-4">
      <label>Poli *</label>
      <select class="form-control" id="f_poli" name="poli" required>
        <option value="">— Pilih poli —</option>
        <?php foreach ($polis as $p): ?>
        <option value="<?= e($p['poli']) ?>" data-home="<?= e($p['home']) ?>" <?= $old['poli']===$p['poli']?'selected':'' ?>>
          <?= e($p['home']) ?> / <?= e($p['poli']) ?><?= (int)$p['active']!==1?' (nonaktif)':'' ?>
        </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group col-md-4">
      <label>Jenis</label>
      <input class="form-control" name="jenis" list="jenislist" value="<?= e($old['jenis']) ?>">
      <datalist id="jenislist"><?php foreach ($jenis_list as $j): ?><option value="<?= e($j) ?>"><?php endforeach; ?></datalist>
    </div>
  </div>
  <div class="form-group">
    <label>File PDF * (max 100MB)</label>
    <input type="file" class="form-control-file" name="pdffile" accept=".pdf,application/pdf" required>
  </div>
  <div class="form-check mb-3">
    <input type="checkbox" class="form-check-input" id="active" name="active" value="1" <?= $old['active']==='1'?'checked':'' ?>>
    <label class="form-check-label" for="active">Aktif (tampil di katalog)</label>
  </div>
  <button class="btn btn-primary" type="submit"><i class="fas fa-upload"></i> Upload & Simpan</button>
  <a class="btn btn-light border" href="dokumen">Batal</a>
</form></div>
<script>
document.getElementById('f_home').addEventListener('change', function(){
  var h = this.value, sel = document.getElementById('f_poli');
  for (var i=0;i<sel.options.length;i++){
    var o = sel.options[i];
    if(!o.value){o.hidden=false;continue;}
    o.hidden = (h && o.getAttribute('data-home')!==h);
  }
  sel.value='';
});
</script>
<?php require __DIR__ . '/inc/footer.php'; ?>
