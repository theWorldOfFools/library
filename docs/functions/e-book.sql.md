<!-- file: docs/functions/e-book.sql.md -->
# e-book.sql

**Tujuan**: dump schema + seed data database `e-book` (MySQL/InnoDB, utf8mb4).
Diimport sekali untuk menyiapkan tabel yang dipakai aplikasi.

**Tabel yang dibuat** (diverifikasi dari source):

| Tabel | Kolom | Dipakai oleh |
|---|---|---|
| `dokumen` | namafile, logo, no, jenis, poli, active, jumlah_view | `index.php`, `index2.php`, `search.php`, `book-detail.php`, `insert.php`, `download.php` |
| `home` | home, logo | `folder.php` |
| `keperawatan` | (seperti `dokumen` + `nama`) | tidak ada query aktif di source `[unverified]` — tampaknya tabel legacy |
| `master_nama` | nama, logo, poli | tidak ada query aktif di source `[unverified]` — data master perawat |
| `poli_keperawatan` | poli, logo | tidak ada query aktif di source `[unverified]` |
| `poli_m` | poli, logo, active, home | `sub-folder.php`, `poli.php` (query `select * from poli_m` tanpa filter) |
| `user` | username, password | `login.php` |

**Catatan**:
- Semua kolom `DEFAULT NULL` — tidak ada constraint/primary key sama sekali.
- Seed `user`: 4 akun dengan hash MD5 yang **sama** untuk semua (`cf862b9e...`)
  `[unverified]` untuk password aslinya — password lemah/identik.
- Seed `poli_m` memetakan poli ke `home` (library/keperawatan/mutu/farmasi) dengan
  flag `active` — dasar filter `sub-folder.php`.
- Seed `home`: library, keperawatan, mutu, farmasi, "Unit Test".
