<!-- file: docs/functions/coba2.php.md -->
# coba2.php

**Tujuan**: skrip uji mandiri untuk logika `scandir` + deteksi ekstensi gambar —
prototipe logika yang dipakai `book-detail2.php` (mencari file gambar dalam folder
perawat).

**Input**: tidak ada — path folder di-hardcode `H:\tolong\IGD\Agung Supriyanto`
(Windows, tidak ada di environment Linux ini).

**Output** (ke stdout): `count($files1)`, `print_r($files1)`, dan `$img_type` (nama
file gambar terakhir yang ditemukan).

**Pemanggil**: tidak ada — skrip uji mandiri.

**Perilaku gagal**:
- Path Windows tidak ada di Linux → `scandir` warning + `false`; `count(false)`
  (notice, mengembalikan 1 di PHP 7 / error di PHP 8 `[unverified]` untuk versi PHP
  yang dipakai), loop tidak jalan, `$img_type` undefined.
- Logika deteksi ekstensi memakai `explode('.', ...)` + `end()` — gagal untuk nama
  file tanpa titik atau dengan titik ganda (mis. `ijazah ika.pdf` aman, tapi
  `file.tar.gz` akan mengembalikan `gz`).
- Hanya ekstensi `jpg`/`png` yang dicek (tidak seperti `book-detail2.php` yang juga
  cek `jpeg`/uppercase) — prototipe lebih sederhana.
