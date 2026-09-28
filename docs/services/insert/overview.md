<!-- file: docs/services/insert/overview.md -->

# insert — POST Handler View Counter

## Tanggung Jawab

Menerima POST dari form `#theform` di `book-detail.php` dan menaikkan `jumlah_view` dokumen terkait di tabel `dokumen`.

## Perilaku

- Parameter POST: `namafile` (nama dokumen), `counter` (value "1", tidak dipakai dalam query).
- Query: `select * from dokumen where namafile='<namafile>'` → ambil `jumlah_view` → `+1` → `update dokumen SET jumlah_view=<n> where namafile='<namafile>'`.
- Tidak ada output/redirect setelah update — eksekusi selesai tanpa respon. [observasi dari sumber]
- Tanpa auth check. Rentan SQL injection (POST langsung interpolasi ke query).
