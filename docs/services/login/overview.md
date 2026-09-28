<!-- file: docs/services/login/overview.md -->

# login — Autentikasi

## Tanggung Jawab

Memverifikasi kredensial user dan membuat session untuk mengakses area keperawatan/mutu.

## Perilaku

- Form POST: field `email` (username) dan `password`; password di-hash `md5()` sebelum dibandingkan.
- Query: `select * FROM user WHERE username='<u>' AND password='<md5>'`.
- **Sukses**: `$_SESSION['username'] = $row['username']`; redirect terjadi di bawah (jika `$_SESSION['username']` terisi → `Location: sub-folder?home=keperawatan&page=1`). [diverifikasi dari sumber]
- **Gagal**: `alert('Username atau Password Anda salah. Silahkan coba lagi!')`.
- `error_reporting(0)` — error disembunyikan; query SQL di-echo ke output (sisa debug).
- Catatan keamanan: password MD5 tanpa salt; tidak ada halaman logout — `session_destroy()` hanya dipanggil saat expiry di modul lain.
