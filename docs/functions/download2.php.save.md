<!-- file: docs/functions/download2.php.save.md -->
# download2.php.save

**Tujuan**: file backup dari `download2.php` (ekstensi `.save`, kemungkinan sisa
edit). Secara fungsional identik dengan `download2.php` kecuali path root folder.

**Perbedaan dari `download2.php`** (diverifikasi dari source):
- Path root memakai `D:/File_Dok_Lib/poli/...` (Windows, drive D) — tidak akan jalan
  di environment Linux saat ini; `download2.php` memakai path relatif
  `File_Dok_Lib/poli/...`.
- MIME type file ini terdeteksi `application/octet-stream` (bukan PHP) — kemungkinan
  hasil edit/save tool yang mengubah isi byte.

**Input/Output/Perilaku gagal**: sama dengan `download2.php` (path traversal, file
tidak ada → respons kosong, encoding `!`→`&`).

**Catatan**: file sebaiknya dihapus dari repo production untuk menghindari kebingungan.
