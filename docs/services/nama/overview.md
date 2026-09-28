<!-- file: docs/services/nama/overview.md -->

# nama — Listing Nama Perawat per Unit (Basis Filesystem)

## Tanggung Jawab

Menampilkan sub-direktori (nama perawat) di `File_Dok_Lib/poli/<poli>/` (scandir) — langkah kedua alur "File Kepegawaian".

## Perilaku

- Redirect `folder` jika param `poli` hilang.
- **Session**: jika `time() > $_SESSION['expire']` → `session_destroy()` + redirect `login`. [diverifikasi dari sumber]
- Contoh isi `File_Dok_Lib/poli/IGD/`: 24 nama perawat (Agung Supriyanto, Anisah, dst.) — cocok dengan data tabel `master_nama` di skema, tetapi listing murni dari filesystem. [diverifikasi dari `ls`; kecocokan dengan `master_nama` adalah observasi]
- Link kartu: `index3?poli=<poli>&nama=<nama>&page=1`.
- Query `select * from poli_m` hanya untuk label paginasi.
