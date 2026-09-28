<!-- file: docs/services/poli/api.md -->

# poli — API Publik

N/A — halaman HTTP murni.

Kontrak lintas modul:
- **Parameter GET**: `page` (label paginasi).
- **Dipanggil oleh**: redirect `index.php` (poli = "File Kepegawaian").
- **Redirect keluar**: `login` (session expire).
- **Link keluar**: `nama?poli=<direktori>&page=1` per unit.
- **Session**: memeriksa `$_SESSION['expire']` (dibuat `sub-folder.php`).
