<!-- file: docs/services/poli/overview.md -->

# poli — Listing Unit (Basis Filesystem)

## Tanggung Jawab

Menampilkan direktori unit di `File_Dok_Lib/poli/` (scandir) — langkah pertama alur "File Kepegawaian" (dialihkan dari `index.php` untuk poli "File Kepegawaian").

## Perilaku

- **Session**: jika `time() > $_SESSION['expire']` → `session_destroy()` + redirect `login`. Tidak ada pemeriksaan `$_SESSION['username']`. [diverifikasi dari sumber]
- Listing dari `scandir('File_Dok_Lib/poli')` (skip `.`/`..`). Direktori yang ada di server: HD, IB, ICU, IGD, MIDEL, Nilam, RC, Supervisor. [diverifikasi dari `ls`]
- Link kartu: `nama?poli=<direktori>&page=1`.
- Query `select * from poli_m` hanya untuk label paginasi (seluruh baris), bukan untuk listing.
