<!-- file: docs/services/search/api.md -->

# search — API Publik

N/A — halaman HTTP murni.

Kontrak lintas modul:
- **Parameter GET**: `search` (kata kunci LIKE pada `namafile`), `page` (paginasi berbasis kolom `no`).
- **Dipanggil oleh**: semua halaman via JS `kirim()` (form hero → `search?search=<teks>`).
- **Link keluar**: `book-detail?subject=<namafile>` per kartu hasil.
