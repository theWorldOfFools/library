<!-- file: docs/functions/index2.php.md -->
# index2.php

**Tujuan**: varian legacy dari `index.php` (katalog dokumen per poli dari tabel
`dokumen`). Hampir identik dengan `index.php` tetapi **tanpa** guard session dan
**tanpa** redirect khusus `File Kepegawaian`.

**Input**: `$_GET['poli']` (dipakai langsung tanpa validasi), `$_GET['page']` (opsional).

**Output**: HTML grid dokumen; kartu mengarah ke `book-detail?subject=<namafile>`.

**Perbedaan utama dari `index.php`** (diverifikasi via diff):
- Tidak ada `session_start()` / pengecekan session.
- Tidak ada redirect `File Kepegawaian` → `poli.php`.
- `$_GET['poli']` dipakai tanpa pengecekan `isset` — jika tidak diset, query
  `where poli=''` mengembalikan 0 baris (grid kosong, tanpa redirect).

**Perilaku gagal**: sama dengan `index.php` (SQL injection, paginasi berbasis
`isset($_GET['page'])`, label halaman salah konteks).

**Catatan**: file ini tampaknya snapshot pengembangan; `index3.php` adalah kelanjutannya
(varian keperawatan berbasis filesystem).
