<!-- file: docs/services/index/database.md -->

# index — Tabel yang Digunakan

| Tabel | Operasi | Keterangan |
|---|---|---|
| dokumen | SELECT (read) | `select * from dokumen where poli='$poli'` — jumlah baris untuk label paginasi. |
| dokumen | SELECT (read) | `select * from dokumen where poli='$poli' limit <offset>,<count>` — kartu dokumen per halaman. |
