<!-- file: docs/services/sub-folder/database.md -->

# sub-folder — Tabel yang Digunakan

| Tabel | Operasi | Keterangan |
|---|---|---|
| poli_m | SELECT (read) | `select * from poli_m` — seluruh baris, untuk label paginasi. |
| poli_m | SELECT (read) | `select poli,logo from poli_m where home = '$home' and active = 1` — kartu poli per home. |
