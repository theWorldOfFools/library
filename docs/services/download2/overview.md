<!-- file: docs/services/download2/overview.md -->

# download2 — Stream File Poli/Nama (Tanpa Counter)

## Tanggung Jawab

Meng-stream file dari `File_Dok_Lib/poli/<poli>/<nama>/<dokumen>` — untuk alur "File Kepegawaian".

## Perilaku

- Logika view counter **seluruhnya di-comment** — tidak ada penambahan `jumlah_view`. [diverifikasi dari sumber]
- Parameter GET: `poli`, `nama`, `dokumen` (didecode `strtr($a, "!", "&")`).
- Streaming identik dengan `download.php`: switch Content-type per ekstensi (pdf → inline), `fread` 2048, `exit` di akhir.
- Tanpa auth check.
