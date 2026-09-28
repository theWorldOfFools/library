<!-- file: docs/services/sub-folder/overview.md -->

# sub-folder — Daftar Poli per Home + Session Guard

## Tanggung Jawab

- Menampilkan daftar poli/unit untuk satu `home` dari tabel `poli_m` (hanya `active = 1`).
- **Session guard**: untuk `home = keperawatan` atau `mutu`, user harus sudah login (`$_SESSION['username']`); jika tidak → redirect `login`.
- Membuat masa berlaku sesi: `$_SESSION['start'] = time()`, `$_SESSION['expire'] = start + 10 menit` (600 detik).

## Perilaku

- Param `home` tidak punya guard eksplisit — jika kosong, query `where home = ''` menghasilkan halaman kosong. [diverifikasi dari sumber]
- Link kartu: `index?poli=<poli>&page=1`. Untuk poli RKK/farmasi/BUDAYA ini memunculkan katalog dari tabel `dokumen` via `index.php`; untuk poli "File Kepegawaian", `index.php` me-redirect ke `poli.php`.
- Label paginasi: `$b = ceil(mysqli_num_rows(select * from poli_m) / 16)` — menghitung seluruh baris `poli_m`, bukan per home. [diverifikasi dari sumber]
