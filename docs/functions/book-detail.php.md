<!-- file: docs/functions/book-detail.php.md -->
# book-detail.php

**Tujuan**: halaman detail satu dokumen e-book. Menampilkan nama file, jumlah view,
dan tombol "View" yang mengarah ke `download.php` (stream PDF inline). Menyediakan
form tersembunyi yang meng-POST ke `insert.php` (penghitung view).

**Input**: `$_GET['subject']` (wajib) — nama file dokumen, dicocokkan ke kolom
`namafile` tabel `dokumen`.

**Output**: HTML detail; tombol View → `download?file=<subject>#toolbar=0`;
form POST `insert.php` dengan field `namafile=<subject>`.

**Pemanggil**: `index.php`, `search.php` (link "View more").

**Perilaku gagal / bug**:
- **SQL injection**: `$_GET['subject']` diinterpolasi langsung ke query.
- **Jumlah view**: diambil dari kolom `jumlah_view` via loop `while` (mengambil baris
  terakhir — redundan karena `namafile` seharusnya unik). Increment dilakukan oleh
  `insert.php` (dipanggil via form), bukan di sini.
- **Script orphan**: baris `<script>instance.setViewState(...)</script>` di luar `<head>`
  merujuk variabel `instance` yang tidak pernah didefinisikan di file ini — sisa
  eksperimen PDF viewer, akan melempar error JS di console.
- Form `#theform` tidak punya submit button dan tidak pernah disubmit oleh kode apa pun
  di file ini — penghitung view hanya jalan jika dipicu dari tempat lain `[unverified]`.
- Jika `subject` tidak ditemukan → `$jumlah_view` undefined (notice), halaman tetap
  render dengan view kosong.
