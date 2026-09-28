<!-- file: docs/services/index3/api.md -->

# index3 — API Publik

N/A — halaman HTTP murni.

Kontrak lintas modul:
- **Parameter GET**: `poli` (unit), `nama` (nama perawat), `page` (label paginasi).
- **Dipanggil oleh**: `nama.php` (link kartu nama).
- **Redirect keluar**: `folder` (param hilang), `login` (session expire).
- **Link keluar**: `book-detail2?poli=...&nama=...&dokumen=...` per file.
- **Session**: memeriksa `$_SESSION['expire']` (dibuat `sub-folder.php`).
