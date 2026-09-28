<!-- file: docs/services/search/overview.md -->

# search — Pencarian Dokumen

## Tanggung Jawab

Mencari dokumen di tabel `dokumen` berdasarkan kemiripan `namafile` (LIKE) dan menampilkan hasil dalam galeri paginasi.

## Perilaku & Catatan Bug

- Query filter: `select namafile,logo from dokumen where namafile LIKE '%<search>%'`.
- **Paginasi mengabaikan filter pencarian**: query halaman memakai `select * from dokumen where no between <r1> and <r2>` — tanpa klausa LIKE — sehingga halaman 2+ menampilkan dokumen di luar hasil pencarian. [diverifikasi dari sumber `search.php`]
- Query `select * from dokumen` (tanpa where) dieksekusi tapi hasilnya tidak dipakai (dead query).
- Paginasi 16/halaman berbasis rentang kolom `no`: `page=1` → `no between 1 and 16`; `page>1` → `no between (N*16-15) and (N*16)`.
- Link kartu: `book-detail?subject=<namafile>`.
- Dipanggil dari semua halaman via JS `kirim()` (inline) pada form hero.
- Tanpa auth check.
