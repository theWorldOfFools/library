<!-- file: docs/functions/sub-folder.php.md -->
# sub-folder.php

**Tujuan**: halaman level-2: daftar poli (unit kerja) milik satu kategori `home`,
dari tabel `poli_m` dengan filter `active = 1`. Membuat session login 10 menit dan
melindungi kategori `keperawatan`/`mutu`.

**Input**:
- `$_GET['home']` (wajib) — nama kategori, dicocokkan ke kolom `home` tabel `poli_m`.
- `$_GET['page']` (opsional) — hanya memunculkan tombol Previous/Next (link ke
  `sub-folder?page=N` tanpa parameter `home` — bug: navigasi paginasi kehilangan konteks).

**Output**: HTML grid poli; tiap kartu mengarah ke `index?poli=<poli>&page=1`.

**Pemanglik**: `folder.php` (link "View more"), navbar.

**Perilaku gagal / keamanan**:
- **Guard login**: jika `home` = `keperawatan` atau `mutu` dan `$_SESSION['username']`
  belum terisi → `header("Location:login")` (tanpa `exit` setelahnya — eksekusi
  lanjut, halaman tetap ter-render sebelum redirect terjadi).
- **Session**: `$_SESSION['start'] = time(); $_SESSION['expire'] = start + 600` (10 menit).
  Tidak ada pengecekan expire di file ini (hanya di `poli.php`/`nama.php`/`index3.php`).
- Query `select poli,logo from poli_m where home='$home' and active=1` — interpolasi
  `$_GET['home']` langsung ke SQL (SQL injection).
- Jika `home` tidak ada di tabel → grid kosong, tanpa pesan error.
