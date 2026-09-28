<!-- file: docs/services/db/database.md -->

# db — Tabel yang Digunakan

Modul ini tidak memiliki tabel sendiri; ia terhubung ke database **`e-book`** yang berisi seluruh tabel aplikasi (dari skema `e-book.sql`):

| Tabel | Keterangan |
|---|---|
| dokumen | katalog dokumen: namafile, logo, no, jenis, poli, active, jumlah_view |
| home | folder tingkat atas: home, logo |
| poli_m | master poli/unit: poli, logo, active, home |
| user | akun: username, password (MD5) |
| keperawatan | dokumen keperawatan per nama — tidak di-query modul mana pun |
| master_nama | master nama perawat per poli — tidak di-query modul mana pun |
| poli_keperawatan | daftar poli keperawatan — tidak di-query modul mana pun |

Detail query per modul lihat `database.md` masing-masing layanan.
