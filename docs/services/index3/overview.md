<!-- file: docs/services/index3/overview.md -->

# index3 — Listing Dokumen Per Perawat (Basis Filesystem)

## Tanggung Jawab

Menampilkan file dokumen milik satu perawat pada satu unit dengan path filesystem `File_Dok_Lib/poli/<poli>/<nama>/` (scandir) — langkah ketiga alur "File Kepegawaian".

## Perilaku

- **Session**: tanpa param `poli` atau `nama` → redirect `folder`; jika `time() > $_SESSION['expire']` → `session_destroy()` + redirect `login`. Sesi dibuat oleh `sub-folder.php` (10 menit).
- **Tidak ada pemeriksaan `$_SESSION['username']']** pada halaman ini — hanya expiry. [diverifikasi dari sumber]
- Listing murni dari filesystem; query `select * from dokumen where poli='$poli'` hanya dipakai untuk menghitung total baris (label paginasi).
- Link kartu: `book-detail2?poli=<poli>&nama=<nama>&dokumen=<nama_file>` — nama file di-encode `strtr($file, "&", "!")` untuk menghindari konflik karakter `&` di URL.
