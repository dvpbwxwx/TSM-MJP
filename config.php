<?php
// Membuat koneksi menggunakan MySQLi (port default 3306, jika Anda menggunakan port 8111 pastikan MySQL mendengarkan di port tersebut)
$connect = mysqli_connect('localhost', 'root1', '', 'aes', 8111);

// Periksa koneksi
if (!$connect) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
