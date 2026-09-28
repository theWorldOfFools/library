<!-- file: docs/functions/index.md -->
# docs/functions — Indeks Dokumentasi Fungsi Publik

Dokumentasi fungsi/perilaku publik per file source pada repo `/var/www/html/library`
(perpustakaan digital RS, PHP prosedural + MySQLi, tanpa framework).

Setiap catatan mencakup: tujuan, input, output, pemanggil, dan perilaku gagal.
Faktual hanya dari source; hal yang tidak bisa diverifikasi ditandai `[unverified]`.

## Peta aplikasi (alur navigasi)

```
folder.php  ──(klik kategori)──> sub-folder.php ──(klik poli)──> index.php ──(klik buku)──> book-detail.php ──(View)──> download.php
     │                                │                              │
     │                                │                              └──> search.php (via form pencarian, JS kirim())
     │                                └──> poli.php (khusus "File Kepegawaian") ──> nama.php ──> index3.php ──> book-detail2.php ──> download2.php
     └──> login.php (guard session untuk home 'keperawatan'/'mutu')
```

## Daftar catatan per file

| File | Peran | Tabel/FS utama |
|---|---|---|
| [db.php](db.php.md) | Koneksi MySQLi ke DB `e-book` | — |
| [folder.php](folder.php.md) | Beranda: daftar kategori (home) | `home` |
| [sub-folder.php](sub-folder.php.md) | Daftar poli per kategori + guard login | `poli_m` |
| [index.php](index.php.md) | Katalog dokumen per poli, paginasi 16/halaman | `dokumen` |
| [index2.php](index2.php.md) | Varian legacy `index.php` (tanpa guard session) | `dokumen` |
| [index3.php](index3.php.md) | Katalog file keperawatan per orang (filesystem) | `File_Dok_Lib/poli/<poli>/<nama>/` |
| [poli.php](poli.php.md) | Daftar folder poli kepegawaian (filesystem) + cek session expire | `File_Dok_Lib/poli/` |
| [nama.php](nama.php.md) | Daftar sub-folder nama dalam poli (filesystem) + guard session | `File_Dok_Lib/poli/<poli>/` |
| [search.php](search.php.md) | Pencarian dokumen berdasarkan nama file | `dokumen` |
| [book-detail.php](book-detail.php.md) | Detail dokumen + tombol View + form penghitung view | `dokumen` |
| [book-detail2.php](book-detail2.php.md) | Detail file keperawatan (filesystem) + tombol View | `File_Dok_Lib/poli/<poli>/<nama>/` |
| [login.php](login.php.md) | Autentikasi user (MD5) + set session | `user` |
| [insert.php](insert.php.md) | Increment `jumlah_view` dokumen (POST) | `dokumen` |
| [download.php](download.php.md) | Stream file dari `File_Dok_Lib/uploads/` + increment view | `dokumen` |
| [download2.php](download2.php.md) | Stream file dari `File_Dok_Lib/poli/<poli>/<nama>/` | — |
| [download2.php.save](download2.php.save.md) | Backup lama `download2.php` (path `D:\`) | — |
| [coba.php](coba.php.md) | Halaman uji form (Enter → search) | — |
| [coba2.php](coba2.php.md) | Skrip uji `scandir` + deteksi ekstensi gambar | — |
| [.htaccess](.htaccess.md) | Rewrite URL `xxx` → `xxx.php`; DirectoryIndex `folder.php` | — |
| [e-book.sql](e-book.sql.md) | Schema + seed data database `e-book` | semua tabel |

## Catatan penting (diverifikasi dari source)

- **SQL injection**: semua query memakai interpolasi string `$_GET`/`$_POST` langsung
  (contoh: `index.php`, `search.php`, `login.php`, `insert.php`, `download.php`).
  Tidak ada prepared statement di seluruh repo.
- **Path traversal**: `download.php` dan `download2.php` menggabungkan `$_GET` mentah ke path
  file tanpa sanitasi (`$path . $_GET['file']`).
- **Password**: `login.php` membandingkan `md5($_POST['password'])` dengan kolom `password`
  di tabel `user` — MD5 tanpa salt. Hash seed di `e-book.sql` bukan MD5 dari string umum
  yang diuji (`rswb`, `admin`, `123456`, `password`) `[unverified]` untuk nilai aslinya.
- **Session**: `sub-folder.php` membuat session 10 menit (`expire = start + 600`) dan
  mengalihkan ke `login` untuk home `keperawatan`/`mutu` jika belum login.
  `poli.php`/`nama.php`/`index3.php` memeriksa `$_SESSION['expire']` tapi **tidak** membuatnya
  — jika session belum pernah dibuat, `$_SESSION['expire']` undefined dan guard tidak
  berfungsi (perbandingan `time() > null` = false) `[perlu runtime untuk konfirmasi]`.
- **Kredensial DB** (`db.php`): host `192.168.214.103`, user `tsany`, password plaintext
  di source. Jangan commit kredensial seperti ini ke repo publik.
- **Repo git belum punya commit** (`main` branch, 0 commit) — seluruh file untracked.
