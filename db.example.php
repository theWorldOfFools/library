<?php
// db.example.php — template koneksi database.
// Copy menjadi db.php lalu isi sesuai server masing-masing.
// db.php TIDAK di-commit (lihat .gitignore) agar kredensial tidak bocor ke GitHub.
$host = "127.0.0.1"; // host MySQL
$user = "dbuser";    // username MySQL
$pass = "dbpass";    // password MySQL
$db   = "e-book";    // nama database

$conn = mysqli_connect($host, $user, $pass, $db);
