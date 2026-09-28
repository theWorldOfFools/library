<!-- file: docs/functions/download.php.md -->
# download.php

**Tujuan**: stream file dokumen dari `File_Dok_Lib/uploads/` ke browser (inline untuk
PDF, octet-stream untuk lainnya). Sebelumnya menaikkan `jumlah_view` di tabel `dokumen`.

**Input**: `$_GET['file']` (wajib) — nama file relatif terhadap `File_Dok_Lib/uploads/`.

**Output**: isi file (binary) dengan header `Content-type`, `Content-Disposition`,
`Content-length`, `Cache-control: private`. `exit` setelah streaming.

**Pemanggil**: `book-detail.php` (tombol View).

**Perilaku gagal / keamanan**:
- **Path traversal**: `$fullPath = "File_Dok_Lib/uploads/" . $_GET['file']` tanpa
  sanitasi — input seperti `../../config` bisa membaca file di luar folder uploads.
- **SQL injection**: `$_GET['file']` diinterpolasi langsung ke query SELECT/UPDATE
  (pola sama dengan `insert.php`).
- **File tidak ada**: `fopen` gagal → `$fd` false → blok if dilewati, `fclose(false)`
  warning, `exit` tetap dijalankan — respons kosong tanpa pesan error.
- **Header rusak jika file tidak ada**: `filesize()`/`pathinfo()` dipanggil hanya di
  dalam blok if, jadi tidak ada header error yang dikirim.
- **Memory**: streaming per 2048 byte via `fread` — aman untuk file besar.
- **Update view sebelum file dicek**: `jumlah_view` dinaikkan meski file tidak ada.
