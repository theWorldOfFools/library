<!-- file: docs/services/download/api.md -->

# download — API Publik

N/A — endpoint file, bukan library.

Kontrak lintas modul:
- **Parameter GET**: `file` (nama file di `File_Dok_Lib/uploads/`).
- **Dipanggil oleh**: `book-detail.php` (tombol "View").
- **Efek samping**: increment `jumlah_view` pada tabel `dokumen` (duplikasi logika yang juga ada di `insert.php`).
- **Output**: binary stream file (PDF inline / octet-stream).
