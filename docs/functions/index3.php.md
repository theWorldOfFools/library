<!-- file: docs/functions/index3.php.md -->
# index3.php

**Tujuan**: katalog file keperawatan per orang. Menggantikan `index2.php` untuk alur
keperawatan: membaca isi folder `File_Dok_Lib/poli/<poli>/<nama>/` dari filesystem
(bukan database) dan menampilkan setiap file sebagai kartu.

**Input**:
- `$_GET['poli']` (wajib) — nama poli, mis. `igd`.
- `$_GET['nama']` (wajib) — nama perawat (nama folder).
- `$_GET['page']` (opsional) — hanya untuk tombol Previous/Next (link ke
  `sub-folder?page=N` — bug: kehilangan parameter `poli`/`nama`).

**Output**: HTML grid file; tiap kartu mengarah ke
`book-detail2?poli=<poli>&page=1&dokumen=<namafile>&nama=<nama>`.
Nama file di-encode khusus: `strtr($file, "&", "!")` (karakter `&` diganti `!` agar
tidak merusak query string).

**Pemanggil**: `nama.php` (link "View more").

**Perilaku gagal / keamanan**:
- **Guard session**: memeriksa `$_SESSION['expire']` — jika `time() > expire` →
  `session_destroy()` + redirect `login`. Tapi file ini **tidak membuat** session;
  jika `$_SESSION['expire']` belum pernah diset (session baru), perbandingan
  `time() > null` bernilai false → guard dilewati `[perlu runtime untuk konfirmasi]`.
- **Path traversal**: `$_GET['poli']` dan `$_GET['nama']` digabung langsung ke path
  filesystem (`File_Dok_Lib/poli/...`) tanpa sanitasi — input seperti `../` bisa
  membaca folder lain.
- `scandir()` tanpa cek keberadaan — folder tidak ada → warning + `false`, loop `for`
  tidak jalan (grid kosong).
- Ekstensi file tidak difilter — semua file (termasuk non-dokumen) ditampilkan.
