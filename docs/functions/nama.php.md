<!-- file: docs/functions/nama.php.md -->
# nama.php

**Tujuan**: halaman level keperawatan: mendaftar sub-folder (nama-nama perawat) di
`File_Dok_Lib/poli/<poli>/` via `scandir()`, lalu mengarah ke `index3.php`.

**Input**:
- `$_GET['poli']` (wajib) — jika tidak ada → redirect `folder`.
- `$_GET['page']` (opsional) — hanya untuk tombol Previous/Next (menautkan ke
  `sub-folder?page=N` — bug: kehilangan parameter `poli`).

**Output**: HTML grid folder nama; tiap kartu mengarah ke
`index3?poli=<poli>&page=1&nama=<nama>`.

**Pemanggil**: `poli.php` (link "View more").

**Perilaku gagal / keamanan**:
- **Guard session expire**: sama dengan `poli.php` — memeriksa `$_SESSION['expire']`
  tanpa membuatnya; guard tidak efektif jika session belum pernah dibuat
  `[perlu runtime untuk konfirmasi]`.
- **Path traversal**: `$_GET['poli']` digabung langsung ke path filesystem tanpa
  sanitasi.
- `scandir()` tanpa cek error — folder tidak ada → grid kosong.
- Nama folder ditampilkan mentah ke HTML tanpa escaping.
