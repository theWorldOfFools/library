<!-- file: docs/services/sub-folder/api.md -->

# sub-folder — API Publik

N/A — halaman HTTP murni.

Kontrak lintas modul:
- **Parameter GET**: `home` (nama folder tingkat atas), `page` (hanya untuk label paginasi).
- **Dipanggil oleh**: `folder.php` (link kartu home).
- **Session**: menyetel `$_SESSION['start']` / `$_SESSION['expire']`; memeriksa `$_SESSION['username']` untuk home keperawatan/mutu.
- **Redirect keluar**: `login` (session tidak ada, home keperawatan/mutu); `index?poli=...` (link kartu).
