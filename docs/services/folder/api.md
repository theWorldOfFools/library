<!-- file: docs/services/folder/api.md -->

# folder — API Publik

N/A — halaman HTTP murni, bukan library; tidak ada fungsi yang dipanggil modul lain.

Kontrak lintas modul (sebagai halaman tujuan):
- **Parameter GET**: none.
- **Dipanggil oleh**: navbar brand semua halaman (`folder`); redirect dari `index.php` (param `poli` kosong), `nama.php` dan `index3.php` (param hilang).
- **Link keluar**: `sub-folder?home=<nama_home>&page=1` per kartu.
