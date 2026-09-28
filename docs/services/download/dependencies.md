<!-- file: docs/services/download/dependencies.md -->

# download — Dependensi

- **db** — koneksi `$conn` (view counter).
- **book-detail** — pemanggil (tombol "View").
- **insert** — duplikasi logika increment `jumlah_view` (yang ini menambah saat halaman unduh dibuka).
- Filesystem: direktori `File_Dok_Lib/uploads/`.
