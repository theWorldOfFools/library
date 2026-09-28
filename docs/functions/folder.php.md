<!-- file: docs/functions/folder.php.md -->
# folder.php

**Tujuan**: halaman beranda (juga `DirectoryIndex` via `.htaccess`). Menampilkan grid
kategori utama (home) dari tabel `home` (library, keperawatan, mutu, farmasi, Unit Test).

**Input**: `$_GET['page']` (opsional, untuk label paginasi — grid tidak benar-benar
dipaginasi; semua baris `home` ditampilkan sekaligus).

**Output**: HTML halaman katalog; tiap kartu mengarah ke
`sub-folder?home=<home>&page=1`. Form pencarian (JS `kirim()`) mengarah ke
`search?search=<query>`.

**Pemanggil**: pengguna (URL `/folder` atau `/` via DirectoryIndex); ditautkan dari
navbar semua halaman (`Library`), brand `index.php`, `nama.php` (redirect jika session
tidak valid).

**Perilaku gagal**:
- Query `select home,logo from home` tanpa error handling — gagal = grid kosong.
- Variabel `$b` (jumlah halaman) dihitung dari tabel `poli_m` (bukan `home`) —
  tidak konsisten dengan konten yang ditampilkan; label "Page X of Y" menampilkan
  angka yang tidak mewakili jumlah halaman grid ini.
- Gambar kartu memakai `img/<logo>`; jika kolom `logo` kosong, src jadi `img/` (gambar
  rusak).
