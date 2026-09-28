<!-- file: docs/services/folder/overview.md -->

# folder — Home / Folder Tingkat Atas

## Tanggung Jawab

- **Entry point aplikasi**: `folder.php` ditetapkan sebagai `DirectoryIndex` di `.htaccess`.
- Menampilkan kartu folder tingkat atas (library, keperawatan, mutu, farmasi) dari tabel `home`.
- Setiap kartu menautkan ke `sub-folder?home=<nama_home>&page=1`.

## Perilaku

- Akses **publik** — tidak ada pemeriksaan session/login.
- Query: `select home,logo from home` — tanpa filter, semua baris ditampilkan (termasuk baris "Unit Test" di data).
- Navbar memuat tautan eksternal: `Dokumen Keperewatan.zip` dan `Dokumen Nakesla.zip` di `http://192.168.214.191:8887/download/...` (server file internal). [URL diverifikasi dari sumber]
- Navbar brand "Catalog E-book" di semua halaman menautkan kembali ke `folder`.

## Alur

`GET /` (DirectoryIndex) → daftar home → user pilih → `sub-folder?home=...`
