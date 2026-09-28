<!-- file: docs/functions/download2.php.md -->
# download2.php

**Tujuan**: stream file keperawatan dari `File_Dok_Lib/poli/<poli>/<nama>/` ke browser
(inline untuk PDF, octet-stream untuk lainnya). Versi keperawatan dari `download.php`
— **tidak** menaikkan `jumlah_view` (bagian itu dikomentari).

**Input**:
- `$_GET['dokumen']` (wajib) — nama file; karakter `!` ditranslasi ke `&`
  (`strtr($a,"!","&")`) untuk kompatibilitas encoding dari `index3.php`.
- `$_GET['poli']` (wajib), `$_GET['nama']` (wajib) — path folder.

**Output**: isi file (binary) dengan header seperti `download.php`; `exit` setelah
streaming.

**Pemanggil**: `book-detail2.php` (tombol View).

**Perilaku gagal / keamanan**:
- **Path traversal**: `$_GET['poli']`, `$_GET['nama']`, `$_GET['dokumen']` digabung
  langsung ke path tanpa sanitasi.
- **File tidak ada**: `fopen` gagal → blok dilewati, `fclose(false)` warning, `exit`
  — respons kosong tanpa pesan.
- **Encoding `!`→`&`**: jika nama file asli mengandung `&`, query string terpecah
  dan nama file yang diterima tidak lengkap/salah.
- `session_start()` dipanggil tapi session tidak pernah dipakai di file ini.
