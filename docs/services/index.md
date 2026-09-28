<!-- file: docs/services/index.md -->

# Layanan Backend — Aplikasi Perpustakaan E-Book

Aplikasi perpustakaan digital (konteks: rumah sakit — istilah "poli", "RKK/SPK", "keperawatan", "farmasi") berbasis **PHP prosedural murni** tanpa framework. Frontend memakai template Catalog-Z (TemplateMo) + Bootstrap + FontAwesome. Database MariaDB `e-book` diakses melalui satu file koneksi `db.php`; komunikasi antar modul lewat `include`, variabel global `$conn`, serta HTTP GET/POST antar halaman.

## Daftar Layanan

| Layanan | File | Peran |
|---|---|---|
| [db](db/) | db.php | Koneksi database (MySQLi) — fondasi semua modul |
| [folder](folder/) | folder.php | Home / folder tingkat atas (DirectoryIndex) |
| [sub-folder](sub-folder/) | sub-folder.php | Daftar poli per home + session guard |
| [index](index/) | index.php | Katalog e-book per poli (basis DB) |
| [index3](index3/) | index3.php | Listing dokumen per perawat (basis filesystem) |
| [poli](poli/) | poli.php | Listing unit di `File_Dok_Lib/poli` (filesystem) |
| [nama](nama/) | nama.php | Listing nama perawat per unit (filesystem) |
| [book-detail](book-detail/) | book-detail.php | Detail e-book katalog (DB) |
| [book-detail2](book-detail2/) | book-detail2.php | Detail dokumen filesystem + preview gambar |
| [download](download/) | download.php | Stream file `uploads/` + view counter |
| [download2](download2/) | download2.php | Stream file `poli/<unit>/<nama>/` (tanpa counter) |
| [insert](insert/) | insert.php | POST handler: increment `jumlah_view` |
| [search](search/) | search.php | Pencarian dokumen |
| [login](login/) | login.php | Autentikasi user (MD5 + session) |

## Alur Utama

**Publik (tanpa login):**
`folder` → `sub-folder?home=library` → `index?poli=...` → `book-detail?subject=...` → `download?file=...` (+ `insert.php` via POST)

**Terprokeksi (login, home keperawatan/mutu):**
`login` → `sub-folder?home=keperawatan` → `index?poli=...` (poli RKK / farmasi / BUDAYA KESELAMATAN) atau `File Kepegawaian` → `poli` → `nama` → `index3` → `book-detail2` → `download2`

**Pencarian:** semua halaman → (JS `kirim()`) → `search?search=...` → `book-detail?subject=...`

## Tabel Database (`e-book`)

| Tabel | Dipakai modul | Keterangan |
|---|---|---|
| dokumen | index, index3 (count), book-detail, download, insert, search | katalog dokumen: namafile, logo, no, jenis, poli, active, jumlah_view |
| home | folder | folder tingkat atas: library, keperawatan, mutu, farmasi |
| poli_m | sub-folder, poli, nama (count) | master poli/unit: poli, logo, active, home |
| user | login | username + password (MD5) |
| keperawatan | — (tidak di-query modul mana pun) | ada di skema SQL saja |
| master_nama | — (tidak di-query modul mana pun) | ada di skema SQL saja |
| poli_keperawatan | — (tidak di-query modul mana pun) | ada di skema SQL saja |

## File Pendukung (bukan layanan backend)

- `index2.php` — varian pengembangan `index.php` (body memakai `scandir` path Windows `H:\tolong`; tidak jalan di Linux).
- `coba.php`, `coba2.php` — skrip uji pengembangan (form JS + scandir).
- `index.html`, `about.html`, `contact.html`, `videos.html`, `video-detail.html` — halaman statis template (frontend murni).
- `e-book.sql` — dump skema + data (phpMyAdmin 5.2.0, MariaDB 10.4.24, PHP 8.1.6).

## Catatan Penting (diverifikasi dari sumber)

- Semua query DB memakai interpolasi langsung `$_GET`/`$_POST` ke SQL — rentan SQL injection di semua modul.
- Password disimpan sebagai MD5 tanpa salt.
- `download.php`, `search.php`, `index.php`, `book-detail.php` tidak punya auth check.
- Sesi: `sub-folder` membuat `$_SESSION['start']`/`$_SESSION['expire']` (10 menit); `poli`, `nama`, `index3`, `book-detail2` memeriksa expiry → redirect/destroy → `login`.
- Kredensial DB hardcoded di `db.php`.
