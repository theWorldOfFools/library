<!-- file: docs/services/login/database.md -->

# login — Tabel yang Digunakan

| Tabel | Operasi | Keterangan |
|---|---|---|
| user | SELECT (read) | `select * FROM user WHERE username='<u>' AND password='<md5>'` — verifikasi kredensial. Kolom: username, password (MD5 tanpa salt). |
