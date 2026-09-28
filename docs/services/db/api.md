<!-- file: docs/services/db/api.md -->

# db — API Publik

N/A — modul ini bukan library dan tidak mendefinisikan fungsi/method.

Satu-satunya kontrak lintas modul: **variabel global `$conn` (objek mysqli)** yang tersedia di scope setiap file yang menjalankan `include "db.php"`. Pola pemanggilan lintas modul:

```php
include "db.php";
$sql = mysqli_query($conn, "select ...");
$rows = mysqli_fetch_array($sql);
```

Catatan: karena `include` dieksekusi di tengah alur halaman (mis. `book-detail.php` meng-include di tengah HTML), koneksi dibuat ulang per request — tanpa pooling/persistensi.
