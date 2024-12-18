<?php
session_start();
include "../config.php";   // Memasukkan koneksi
include "AES.php"; // Memasukkan file AES

// Menggunakan mysqli_real_escape_string untuk sanitasi input dan koneksi
$idfile    = mysqli_real_escape_string($connect, $_POST['fileid']);
$pwdfile   = mysqli_real_escape_string($connect, substr(md5($_POST["pwdfile"]), 0, 16));

// Query untuk mengecek password
$query     = "SELECT password FROM file WHERE id_file='$idfile' AND password='$pwdfile'";
$sql       = mysqli_query($connect, $query);

// Mengecek apakah ada data yang sesuai
if(mysqli_num_rows($sql) > 0){
    // Mengambil data file
    $query1     = "SELECT * FROM file WHERE id_file='$idfile'";
    $sql1       = mysqli_query($connect, $query1);
    $data       = mysqli_fetch_assoc($sql1);

    $file_path  = $data["file_url"];
    $key        = $data["password"];
    $file_name  = $data["file_name_source"];
    $size       = $data["file_size"];

    // Mendapatkan ukuran file
    $file_size  = filesize($file_path);

    // Update status file menjadi '2' (terdekripsi)
    $query2     = "UPDATE file SET status='2' WHERE id_file='$idfile'";
    $sql2       = mysqli_query($connect, $query2);

    // Menentukan modulus ukuran file untuk pengolahan AES
    $mod        = $file_size % 16;

    // Menyiapkan AES untuk dekripsi
    $aes        = new AES($key);
    $fopen1     = fopen($file_path, "rb");
    $plain      = "";
    $cache      = "file_decrypt/$file_name";
    $fopen2     = fopen($cache, "wb");

    // Menghitung banyaknya blok yang perlu diproses
    if($mod == 0){
        $banyak = $file_size / 16;
    } else {
        $banyak = ($file_size - $mod) / 16;
        $banyak = $banyak + 1;
    }

    ini_set('max_execution_time', -1);
    ini_set('memory_limit', -1);

    // Proses dekripsi
    for($bawah = 0; $bawah < $banyak; $bawah++){
        $filedata = fread($fopen1, 16);
        $plain = $aes->decrypt($filedata);
        fwrite($fopen2, $plain);
    }

    // Menyimpan path file yang sudah didekripsi di session
    $_SESSION["download"] = $cache;

    // Membuka jendela download dan mengarahkan pengguna
    echo("<script language='javascript'>
           window.open('download.php', '_blank');
           window.location.href='decrypt.php';
           window.alert('Berhasil mendekripsi file.');
         </script>");
} else {
    // Jika password salah
    echo("<script language='javascript'>
           window.location.href='decrypt-file.php?id_file=$idfile';
           window.alert('Maaf, Password tidak sesuai.');
         </script>");
}
?>
