<!-- file: docs/services/book-detail/overview.md -->

# book-detail — Detail E-Book Katalog (Basis DB)

## Tanggung Jawab

Menampilkan halaman detail satu dokumen katalog (library/RKK/farmasi) berdasarkan `subject` = `namafile`.

## Perilaku

- Query: `select * from dokumen where namafile = '<subject>'` → menampilkan judul, `jumlah_view`, dan label format (hardcoded "PDF").
- **Form POST** (`#theform`, `action='insert.php'`) dengan hidden field `counter=1` dan `namafile=<subject>` — bertujuan menaikkan counter view. Catatan: tidak terlihat tombol submit eksplisit untuk form ini di sumber. [unverified: mekanisme submit form tidak jelas dari sumber]
- **Tombol "View"** → `download?file=<subject>#toolbar=0` (stream PDF inline di browser).
- Tanpa auth check (publik); session tidak dipakai.
