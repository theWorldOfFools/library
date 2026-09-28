<!-- file: docs/services/insert/api.md -->

# insert — API Publik

N/A — endpoint POST murni, bukan library.

Kontrak lintas modul:
- **Method**: POST.
- **Parameter**: `namafile` (wajib), `counter` (tidak dipakai).
- **Dipanggil oleh**: `book-detail.php` (form `#theform`, `action='insert.php'`).
- **Efek samping**: increment `jumlah_view` pada tabel `dokumen`.
