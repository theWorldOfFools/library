<!-- file: docs/services/download2/api.md -->

# download2 — API Publik

N/A — endpoint file, bukan library.

Kontrak lintas modul:
- **Parameter GET**: `poli` (unit), `nama` (perawat), `dokumen` (nama file, di-encode `!` untuk `&`).
- **Dipanggil oleh**: `book-detail2.php` (link unduh/lihat).
- **Output**: binary stream file; tanpa efek samping ke database.
