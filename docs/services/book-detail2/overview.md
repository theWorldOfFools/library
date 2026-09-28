<!-- file: docs/services/book-detail2/overview.md -->

# book-detail2 — Detail Dokumen Filesystem + Preview Gambar

## Tanggung Jawab

Menampilkan detail satu dokumen dari alur "File Kepegawaian" (path `File_Dok_Lib/poli/<poli>/<nama>/`), lengkap dengan preview gambar dokumen.

## Perilaku

- **Session**: jika `time() > $_SESSION['expire']` → `session_destroy()` (tanpa redirect eksplisit setelahnya). [diverifikasi dari sumber]
- Parameter: `poli`, `nama`, `dokumen` (didecode `strtr($a, "!", "&")` — kebalikan encoding di `index3.php`).
- scandir direktori; file ber-ekstensi jpg/png/jpeg (case-sensitive) dipakai sebagai gambar preview via `<img src="File_Dok_Lib/poli/...">`.
- Link tombol: `download2?poli=<poli>&nama=<nama>&dokumen=<dokumen>#toolbar=0`.
- Seluruh query `dokumen` di file ini **di-comment** — tidak ada akses DB aktif. [diverifikasi dari sumber]
