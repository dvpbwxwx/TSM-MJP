<?php
session_start();
include "../config.php";   // Memasukkan koneksi
include "AES.php"; // Memasukkan file AES

if (isset($_POST['encrypt_now'])) {
    $user = $_SESSION['username'];

    // Pastikan password file valid dan hanya mengambil 16 karakter pertama untuk key
    $key = mysqli_real_escape_string($connect, substr(md5($_POST["pwdfile"]), 0, 16));
    $deskripsi = mysqli_real_escape_string($connect, $_POST['desc']);

    $file_tmpname = $_FILES['file']['tmp_name'];

    // Nama file untuk URL dan final
    $file = rand(1000, 100000) . "-" . $_FILES['file']['name'];
    $new_file_name = strtolower($file);
    $final_file = str_replace(' ', '-', $new_file_name);

    $filename = rand(1000, 100000) . "-" . pathinfo($_FILES['file']['name'], PATHINFO_FILENAME);
    $new_filename = strtolower($filename);
    $finalfile = str_replace(' ', '-', $new_filename);

    $size = filesize($file_tmpname);
    $size2 = $size / 1024;  // ukuran file dalam KB
    $info = pathinfo($final_file);
    $ext = $info["extension"];

    // Memeriksa ekstensi file yang diizinkan
    $allowed_extensions = ['docx', 'doc', 'txt', 'pdf', 'xls', 'xlsx', 'ppt', 'pptx'];
    if (!in_array($ext, $allowed_extensions)) {
        echo "<script language='javascript'>
        window.location.href='encrypt.php';
        window.alert('Maaf, file yang bisa dienkrip hanya word, excel, text, ppt ataupun pdf.');
        </script>";
        exit();
    }

    // Memeriksa ukuran file, jika lebih dari 3MB tampilkan pesan
    if ($size2 > 3084) {
        echo "<script language='javascript'>
        window.location.href='home.php?encrypt';
        window.alert('Maaf, file tidak bisa lebih besar dari 3MB.');
        </script>";
        exit();
    }

    // Menyimpan informasi file ke database
    $sql1 = "INSERT INTO file (username, file_name_source, file_name_finish, file_url, file_size, password, tgl_upload, status, keterangan) 
    VALUES ('$user', '$final_file', '$finalfile.rda', '', '$size2', '$key', NOW(), '1', '$deskripsi')";
    $query1 = mysqli_query($connect, $sql1);
    if (!$query1) {
        die("Error: " . mysqli_error($connect));
    }

    // Mengupdate file URL setelah berhasil menyimpan data file
    $url = $finalfile . ".rda";
    $file_url = "file_encrypt/$url";

    $sql3 = "UPDATE file SET file_url = '$file_url' WHERE file_url = ''";
    $query3 = mysqli_query($connect, $sql3);
    if (!$query3) {
        die("Error: " . mysqli_error($connect));
    }

    // Membuka file untuk proses enkripsi
    $file_source = fopen($file_tmpname, 'rb');
    if (!$file_source) {
        die("Error: Gagal membuka file.");
    }

    // Menentukan jumlah blok 16 byte untuk enkripsi
    $mod = $size % 16;
    $banyak = ($mod == 0) ? ($size / 16) : (($size - $mod) / 16) + 1;

    // Membuka file output untuk menulis hasil enkripsi
    $file_output = fopen($file_url, 'wb');
    if (!$file_output) {
        die("Error: Gagal membuka file output untuk ditulis.");
    }

    // Inisialisasi AES dengan key
    $aes = new AES($key);

    // Melakukan enkripsi data file per blok 16 byte
    for ($bawah = 0; $bawah < $banyak; $bawah++) {
        $data = fread($file_source, 16);
        if (strlen($data) == 0) {
            die("Error: Gagal membaca data file.");
        }
        $cipher = $aes->encrypt($data);
        fwrite($file_output, $cipher);
    }

    // Menutup file source dan output setelah selesai
    fclose($file_source);
    fclose($file_output);

    // Memberi tahu pengguna bahwa enkripsi berhasil
    echo "<script language='javascript'>
    window.location.href='encrypt.php';
    window.alert('Enkripsi Berhasil..');
    </script>";
}
?>
