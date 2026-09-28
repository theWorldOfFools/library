<!-- file: docs/services/index/api.md -->

# index — API Publik

N/A — halaman HTTP murni.

Kontrak lintas modul:
- **Parameter GET**: `poli` (wajib), `page` (paginasi, 16 item/halaman).
- **Dipanggil oleh**: `sub-folder.php` (link kartu poli).
- **Redirect keluar**: `folder` (poli kosong), `poli.php` (poli = "File Kepegawaian").
- **Link keluar**: `book-detail?subject=<namafile>` per kartu; `search?search=<teks>` via JS `kirim()` (inline di halaman).
