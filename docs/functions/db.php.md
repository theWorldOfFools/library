<!-- file: docs/functions/db.php.md -->
# db.php

**Tujuan**: satu-satunya titik koneksi database untuk seluruh aplikasi. Di-`include` oleh
semua halaman PHP lainnya.

**Input**: tidak ada (konfigurasi hardcoded di file).

**Output**: variabel `$conn` (mysqli connection) tersedia di scope pemanggil.

**Pemanggil**: `folder.php`, `sub-folder.php`, `index.php`, `index2.php`, `index3.php`,
`poli.php`, `nama.php`, `search.php`, `book-detail.php`, `book-detail2.php`,
`login.php`, `insert.php`, `download.php`, `download2.php`.

**Konfigurasi** (diverifikasi dari source):
- host `192.168.214.103`, user `tsany`, password `Bolo-123` (plaintext), database `e-book`.

**Perilaku gagal**:
- `mysqli_connect()` tanpa error handling — jika koneksi gagal, PHP warning/notice dan
  `$conn` bernilai false; query berikutnya (`mysqli_query($conn, ...)`) akan gagal dengan
  warning, halaman tetap render dengan konten kosong.
- Tidak ada `mysqli_set_charset` — charset koneksi default server.
