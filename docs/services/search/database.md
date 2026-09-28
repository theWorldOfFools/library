<!-- file: docs/services/search/database.md -->

# search — Tabel yang Digunakan

| Tabel | Operasi | Keterangan |
|---|---|---|
| dokumen | SELECT (read) | `select * from dokumen` — dead query, hasil tidak dipakai. |
| dokumen | SELECT (read) | `select namafile,logo from dokumen where namafile LIKE '%<search>%'` — hasil pencarian + count paginasi. |
| dokumen | SELECT (read) | `select * from dokumen where no between <r1> and <r2>` — kartu per halaman (tanpa filter LIKE). |
