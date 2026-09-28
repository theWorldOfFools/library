<!-- file: docs/services/index3/database.md -->

# index3 — Tabel yang Digunakan

| Tabel | Operasi | Keterangan |
|---|---|---|
| dokumen | SELECT (read) | `select * from dokumen where poli='$poli'` — hanya untuk menghitung baris (label paginasi); listing dokumen murni dari filesystem. |
