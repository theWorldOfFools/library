<!-- file: docs/services/login/api.md -->

# login — API Publik

N/A — halaman HTTP + handler POST, bukan library.

Kontrak lintas modul:
- **Method**: POST (field `email`, `password`).
- **Dipanggil oleh**: `sub-folder.php` (redirect saat session tidak ada untuk home keperawatan/mutu), `poli.php`, `nama.php`, `index3.php`, `book-detail2.php` (redirect saat session expire).
- **Session keluar**: `$_SESSION['username']` (dibaca `sub-folder.php`); `$_SESSION['start']`/`$_SESSION['expire']` dibuat di sini? Tidak — dibuat oleh `sub-folder.php`. [diverifikasi]
- **Redirect keluar**: `sub-folder?home=keperawatan&page=1` (session aktif).
