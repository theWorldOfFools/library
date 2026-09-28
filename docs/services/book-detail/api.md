<!-- file: docs/services/book-detail/api.md -->

# book-detail — API Publik

N/A — halaman HTTP murni.

Kontrak lintas modul:
- **Parameter GET**: `subject` (nama file dokumen di tabel `dokumen`).
- **Dipanggil oleh**: `index.php` dan `search.php` (link kartu dokumen).
- **Form POST keluar**: ke `insert.php` (field `namafile`, `counter`).
- **Link keluar**: `download?file=<subject>` (tombol View).
