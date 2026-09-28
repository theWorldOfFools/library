<!-- file: docs/services/download/database.md -->

# download — Tabel yang Digunakan

| Tabel | Operasi | Keterangan |
|---|---|---|
| dokumen | SELECT (read) | `select * from dokumen where namafile = '<file>'` — mengambil `jumlah_view`. |
| dokumen | UPDATE (write) | `update dokumen SET jumlah_view = <n> where namafile = '<file>'` — increment counter tiap unduhan. |
