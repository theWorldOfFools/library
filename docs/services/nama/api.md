<!-- file: docs/services/nama/api.md -->

# nama — API Publik

N/A — halaman HTTP murni.

Kontrak lintas modul:
- **Parameter GET**: `poli` (unit), `page` (label paginasi).
- **Dipanggil oleh**: `poli.php` (link kartu unit).
- **Redirect keluar**: `folder` (param `poli` hilang), `login` (session expire).
- **Link keluar**: `index3?poli=...&nama=...&page=1` per nama.
- **Session**: memeriksa `$_SESSION['expire']` (dibuat `sub-folder.php`).
