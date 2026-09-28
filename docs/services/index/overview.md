<!-- file: docs/services/index/overview.md -->

# index — Katalog E-Book per Poli (Basis DB)

## Tanggung Jawab

Menampilkan katalog dokumen (e-book library, RKK, farmasi) untuk satu `poli` dari tabel `dokumen`, dengan paginasi 16 item per halaman.

## Perilaku

- **Redirect**: tanpa param `poli` → `folder`; `poli = "File Kepegawaian"` → `poli.php`.
- **Paginasi** (`page` = N): query `select * from dokumen where poli = '$poli' limit <offset>,<count>`:
  - `page=1` → `limit 0,16`
  - `page>1` → `limit (N*16-16),(N*16)` — parameter count pada halaman >1 adalah `N*16` (bukan 16), sehingga halaman 2 menampilkan baris 17–48. [diverifikasi dari sumber `index.php`]
- Link kartu: `book-detail?subject=<namafile>`.
- Pencarian: form hero (JS `kirim()`) → `search?search=<teks>`.
- Akses publik, tanpa auth check.

## Varian / File Terkait

- **index2.php** — varian pengembangan: bagian atas sama (query `dokumen`), tapi body memakai `scandir('H:\tolong')` (path Windows) sehingga tidak berfungsi di server Linux; tidak direferensikan modul lain. [diverifikasi dari diff index.php vs index2.php]
- **index3.php** — layanan terpisah untuk dokumen filesystem per perawat (lihat `docs/services/index3/`).
