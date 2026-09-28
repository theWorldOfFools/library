<!-- file: docs/services/db/overview.md -->

# db — Koneksi Database

## Tanggung Jawab

Satu-satunya titik koneksi database aplikasi. Membuat koneksi MySQLi prosedural ke MariaDB dan mengekspos variabel global `$conn` yang dipakai semua modul lain via `include "db.php"`.

## Detail Implementasi (dari sumber `db.php`)

- Kredensial **hardcoded**: host `192.168.214.103`, user `tsany`, password `Bolo-123`, database `e-book`.
- `mysqli_connect($host, $user, $pass, $db)` — tanpa error handling eksplisit.
- Tanpa fungsi/class: modul dieksekusi top-level setiap kali di-`include`, sehingga koneksi baru dibuat per request.

## Modul yang Menggunakan

14 file PHP meng-`include "db.php"`: folder, sub-folder, index, index2, index3, poli, nama, book-detail, book-detail2, download, download2, insert, search, login.

## Catatan Keamanan

Kredensial database plaintext di source code. Tidak ada environment variable / config eksternal.
