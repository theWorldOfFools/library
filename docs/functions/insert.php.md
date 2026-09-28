<!-- file: docs/functions/insert.php.md -->
# insert.php

**Tujuan**: endpoint POST untuk menaikkan penghitung `jumlah_view` dokumen di tabel
`dokumen` (dipanggil dari form tersembunyi `book-detail.php`).

**Input** (POST): `namafile` — nama file dokumen. (Field `counter` ada di form tapi
tidak pernah dibaca file ini.)

**Output**: tidak ada output HTML — hanya UPDATE ke database.

**Pemanggil**: `book-detail.php` (form `#theform`, `action='insert.php'`).

**Perilaku gagal / keamanan**:
- **SQL injection**: `$_POST['namafile']` diinterpolasi langsung ke query SELECT dan UPDATE.
- **Race condition / double increment**: SELECT lalu UPDATE non-atomik — dua request
  bersamaan bisa membaca nilai yang sama dan menimpa increment satu sama lain.
- **Notice jika tidak ketemu**: jika `namafile` tidak ada, `$jumlah_view` undefined
  (notice) dan UPDATE meng-set `jumlah_view = 1` (null+1) untuk baris yang cocok —
  atau 0 baris ter-update jika tidak cocok.
- Tidak ada validasi method (GET pun bisa memicu), tidak ada output sukses/gagal.
