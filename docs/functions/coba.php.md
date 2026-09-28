<!-- file: docs/functions/coba.php.md -->
# coba.php

**Tujuan**: halaman uji (coba = "coba/coba-coba") untuk form pencarian — menguji
peralihan ke `search?search` saat tombol submit ditekan.

**Input**: input teks `#myInputID` (Enter atau klik Submit).

**Output**: halaman HTML minimal; saat Enter ditekan → `location.href("search?search")`
— **bug**: `location.href` adalah properti, bukan fungsi; pemanggilan `location.href(...)`
melempar TypeError di console dan navigasi tidak terjadi. Nilai input juga tidak
 ikut dibawa (URL tidak pernah memakai nilai `#myInputID`).

**Pemanggil**: tidak ada tautan dari halaman lain — file uji mandiri.

**Perilaku gagal**: seperti di atas — navigasi rusak karena salah pakai API.
