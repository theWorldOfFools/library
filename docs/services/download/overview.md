<!-- file: docs/services/download/overview.md -->

# download — Stream File uploads/ + View Counter

## Tanggung Jawab

- Meng-stream file dari `File_Dok_Lib/uploads/<file>` ke browser (inline untuk PDF).
- **Menambah counter `jumlah_view`** di tabel `dokumen` setiap kali file diakses: `select jumlah_view ... where namafile='<file>'` → `+1` → `update dokumen SET jumlah_view=<n>`.

## Perilaku Teknis

- `fopen($fullPath, "r")`; header: `Content-type: application/pdf` + `Content-Disposition: inline` untuk `.pdf`; `application/octet-stream` untuk ekstensi lain; `Content-length` + `Cache-control: private`.
- Streaming via `fread` buffer 2048 byte, lalu `exit`.
- `session_start()` dipanggil tapi **tidak ada auth/permission check**. [diverifikasi dari sumber]
- `error_reporting(E_ALL); ini_set('display_errors', 1);` aktif di file ini.
- Counter di-update sebelum streaming; jika baris `dokumen` tidak ada, `$jumlah_view` undefined → query update bermasalah. [observasi dari sumber]
- Catatan: `$_GET['file']` dipakai langsung sebagai path file tanpa validasi — potensi directory traversal. [observasi dari sumber]
