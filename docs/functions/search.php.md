<!-- file: docs/functions/search.php.md -->
# search.php

**Tujuan**: pencarian dokumen berdasarkan nama file (tabel `dokumen`), pola
`LIKE '%<query>%'`.

**Input**:
- `$_GET['search']` (wajib) — kata kunci pencarian.
- `$_GET['page']` (opsional) — nomor halaman; grid hanya tampil jika diset.

**Output**: HTML grid hasil; kartu mengarah ke `book-detail?subject=<namafile>`.
Paginasi menautkan ke `index?page=N` (bukan `search?...` — bug: nomor halaman
mengarah ke halaman katalog poli, bukan halaman hasil pencarian).

**Pemanggil**: semua halaman dengan form pencarian via JS `kirim()` (folder, index,
sub-folder, poli, nama, search, book-detail, book-detail2).

**Perilaku gagal / bug**:
- **SQL injection**: `$_GET['search']` diinterpolasi langsung ke `LIKE '%$search%'`.
- **Query pertama sia-sia**: `select * from dokumen` dijalankan dan tidak dipakai
  (hasilnya tidak pernah dibaca) — pemborosan resource.
- **Paginasi berbasis `no` (bukan LIMIT)**: `where no between $range1 and $range2`
  dengan `$range2 = page*16`, `$range1 = range2-15` — mengasumsikan kolom `no`
  berurutan tanpa celah; jika ada baris terhapus, hasil paginasi bergeser/kurang.
- Jika `page` tidak diset → grid kosong (tidak ada hasil default).
- Label "Page 1 of 200" di-hardcode (tidak dinamis).
