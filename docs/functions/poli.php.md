<!-- file: docs/functions/poli.php.md -->
# poli.php

**Tujuan**: halaman khusus poli `File Kepegawaian` (dialihkan dari `index.php`).
Mendaftar folder-folder poli di `File_Dok_Lib/poli/` via `scandir()` (filesystem, bukan
database), lalu mengarah ke `nama.php`.

**Input**: `$_GET['page']` (opsional, hanya untuk tombol paginasi yang menautkan ke
`sub-folder?page=N` — bug: kehilangan konteks).

**Output**: HTML grid folder poli; tiap kartu mengarah ke `nama?poli=<folder>&page=1`.

**Pemanggil**: `index.php` (redirect untuk poli `File Kepegawaian`).

**Perilaku gagal / keamanan**:
- **Guard session expire**: `if ($now > $_SESSION['expire'])` → `session_destroy()` +
  redirect `login`. File ini tidak membuat session — jika `$_SESSION['expire']` belum
  pernah diset, guard tidak berfungsi `[perlu runtime untuk konfirmasi]`.
- `scandir('File_Dok_Lib/poli')` tanpa cek error — gagal = grid kosong.
- Loop `for($i=2; $i<=count-1; $i++)` melewati `.` dan `..` (asumsi scandir default
  ascending) — benar untuk scandir tanpa flag.
- Nama folder ditampilkan mentah ke HTML (XSS jika nama folder berisi tag HTML —
  risiko rendah karena nama folder dibuat admin, tapi tidak ada escaping).
