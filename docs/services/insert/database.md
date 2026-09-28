<!-- file: docs/services/insert/database.md -->

# insert — Tabel yang Digunakan

| Tabel | Operasi | Keterangan |
|---|---|---|
| dokumen | SELECT (read) | `select * from dokumen where namafile = '<namafile>'` — mengambil `jumlah_view`. |
| dokumen | UPDATE (write) | `update dokumen SET jumlah_view = <n> where namafile = '<namafile>'` — increment counter. |
