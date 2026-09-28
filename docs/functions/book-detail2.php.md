<!-- file: docs/functions/book-detail2.php.md -->
# book-detail2.php

**Tujuan**: halaman detail file keperawatan (alur filesystem). Menampilkan foto
perawat (file gambar pertama yang ditemukan di folder) dan tombol "View" yang
mengarah ke `download2.php`.

**Input**:
- `$_GET['dokumen']` (wajib) — nama file dokumen.
- `$_GET['poli']` (wajib) — nama poli.
- `$_GET['nama']` (wajib) — nama perawat.

**Output**: HTML detail; tombol View →
`download2?poli=<poli>&nama=<nama>&dokumen=<dokumen>#toolbar=0`.

**Pemanggil**: `index3.php` (link "View more").

**Perilaku gagal / keamanan**:
- **Guard session expire**: memeriksa `$_SESSION['expire']` tanpa membuatnya — tidak
  efektif jika session belum pernah dibuat `[perlu runtime untuk konfirmasi]`.
- **Encoding aneh**: `$dokumen = strtr($a, "!", "&")` — karakter `!` di URL diganti
  `&` (kebalikan encoding di `index3.php`). Jika nama file mengandung `&` asli,
  query string `download2` terpecah dan parameter bergeser.
- **Foto perawat**: loop `scandir` mencari file ber-ekstensi jpg/png/jpeg (case
  sensitive + varian uppercase) dan menyimpan yang **terakhir** ditemukan — bukan
  yang pertama; jika tidak ada gambar, `$img_type` undefined → src path rusak.
- **Path traversal**: `$_GET['poli']`/`$_GET['nama']` digabung langsung ke path
  filesystem tanpa sanitasi.
- `scandir()` tanpa cek error — folder tidak ada → warning, halaman tetap render.
