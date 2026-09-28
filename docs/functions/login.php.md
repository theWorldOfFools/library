<!-- file: docs/functions/login.php.md -->
# login.php

**Tujuan**: halaman autentikasi. Memverifikasi username + password (MD5) terhadap
tabel `user`, lalu membuat session dan mengalihkan ke `sub-folder?home=keperawatan&page=1`.

**Input** (POST): `email` (username), `password`, `submit`.

**Output**:
- Sukses: `$_SESSION['username']` diset → redirect `sub-folder?home=keperawatan&page=1`.
- Gagal: alert JS "Username atau Password Anda salah. Silahkan coba lagi!".

**Pemanggil**: `sub-folder.php`, `poli.php`, `nama.php`, `index3.php`, `book-detail2.php`
(redirect ke sini saat session tidak valid).

**Perilaku gagal / keamanan**:
- **SQL injection**: `$_POST['email']` dan hash password diinterpolasi langsung ke query.
- **Password MD5 tanpa salt**: `md5($_POST['password'])` dibandingkan dengan kolom
  `password` — MD5 rentan terhadap rainbow table. Hash seed di `e-book.sql` sama untuk
  semua user `[unverified]` untuk nilai password aslinya.
- **Error reporting dimatikan**: `error_reporting(0)` — menyembunyikan warning/notice.
- **Redirect tanpa `exit`**: setelah `header("Location: ...")` pada blok session valid,
  eksekusi lanjut dan HTML halaman tetap ter-render (bocor konten sebelum redirect).
- **Debug echo**: `echo $sql;` mencetak query SQL (termasuk hash password) ke halaman —
  sisa debugging yang harus dihapus.
- **Session fixation**: `session_start()` tanpa `session_regenerate_id()` setelah login.
- Form memakai `action=""` (POST ke diri sendiri) — benar, tapi input username memakai
  `type="username"` (type tidak valid; seharusnya `text`).
