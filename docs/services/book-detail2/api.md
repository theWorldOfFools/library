<!-- file: docs/services/book-detail2/api.md -->

# book-detail2 — API Publik

N/A — halaman HTTP murni.

Kontrak lintas modul:
- **Parameter GET**: `poli` (unit), `nama` (perawat), `dokumen` (nama file, di-encode `!` untuk `&`).
- **Dipanggil oleh**: `index3.php` (link kartu file).
- **Link keluar**: `download2?poli=...&nama=...&dokumen=...`.
- **Session**: memeriksa `$_SESSION['expire']` (dibuat `sub-folder.php`).
