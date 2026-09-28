<!-- file: docs/functions/index.php.md -->
# index.php

**Tujuan**: katalog dokumen (e-book) per poli dari tabel `dokumen`, dengan paginasi
16 item per halaman. Khusus poli `File Kepegawaian` dialihkan ke `poli.php`.

**Input**:
- `$_GET['poli']` (wajib) — nama poli; jika tidak ada → redirect `folder`.
- `$_GET['page']` (opsional) — nomor halaman (1-based).

**Output**: HTML grid dokumen; tiap kartu mengarah ke `book-detail?subject=<namafile>`.
Paginasi: `index?page=N&poli=<poli>`.

**Pemanggil**: `sub-folder.php` (link "View more"), `search.php` (link paginasi),
navbar brand.

**Perilaku gagal / bug**:
- **SQL injection**: `$_GET['poli']` diinterpolasi langsung ke query.
- **Paginasi salah untuk halaman > 1**: query memakai `LIMIT $range1, $range2` dengan
  `$range2 = page*16` sebagai *bukan* jumlah baris melainkan offset akhir — pada halaman 2
  query `LIMIT 16,32` mengambil 16 baris mulai baris 17 (kebetulan benar), tapi pola ini
  rapuh dan tidak standar.
- **Bug paginasi halaman 1**: `LIMIT 0,16` benar; namun jika `page` tidak diset, grid
  tidak tampil sama sekali (blok `if (isset($_GET['page']))`).
- **Bug rentang halaman 1 vs >1 tidak simetris**: halaman 1 memakai `limit 0,16`, halaman
  >1 memakai `limit (page*16-16), page*16` — jumlah baris yang diminta tetap 16, jadi
  secara kebetulan konsisten, tapi mudah rusak saat diubah.
- Redirect `File Kepegawaian` → `poli.php` terjadi **sebelum** query, tanpa `exit`
  (eksekusi lanjut; header redirect tetap dikirim).
- Label "Page X of Y" menampilkan `$b` = ceil(jumlah baris `poli_m` / 16) — bukan
  jumlah halaman dokumen yang sedang ditampilkan (salah konten).
