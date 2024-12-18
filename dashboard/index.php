<?php
session_start();
include('../config.php'); // Pastikan config.php berisi detail koneksi database kamu

if(empty($_SESSION['username'])){
    header("location:../index.php");
    exit();
}

$last = $_SESSION['username'];

// Asumsikan $conn adalah koneksi MySQL kamu
$sqlupdate = "UPDATE users SET last_activity=NOW() WHERE username='$last'";

// Gunakan mysqli_query untuk mengeksekusi query
$queryupdate = mysqli_query($connect, $sqlupdate);

if (!$queryupdate) {
    // Tangani error jika query gagal
    echo "Error: " . mysqli_error($connect);
}
?>
<!DOCTYPE html>
<html>
<?php
session_start();
include('../config.php'); // Pastikan file config.php sudah memuat koneksi database

if(empty($_SESSION['username'])){
    header("location:../index.php");
    exit();
}

$user = $_SESSION['username'];

// Pastikan $conn adalah koneksi mysqli kamu
$query = mysqli_query($connect, "SELECT fullname, job_title, last_activity FROM users WHERE username='$user'");

if (!$query) {
    // Tangani error jika query gagal
    echo "Error: " . mysqli_error($connect);
} else {
    $data = mysqli_fetch_array($query);
    // Lakukan hal lain dengan $data
}
?>

  <head>
    <title><?php echo $data['fullname']; ?> - TSM MJP</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../assets/css/main.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries-->
    <!--if lt IE 9
    script(src='https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js')
    script(src='https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js')
    -->
  </head>
  <body class="sidebar-mini fixed">
<!-- NAVBAR SIDEBAR -->
    <?php include('navmenu.php'); ?>

      <div class="content-wrapper">
        <div class="page-title">
          <div>
            <h1><i class="fa fa-home"></i> Dashboard<b></b></h1>
          </div>
          <div>
            <ul class="breadcrumb">
              <li><i class="fa fa-home fa-lg"></i></li>
              <li><a href="#">Beranda</a></li>
            </ul>
          </div>
        </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="card">
                      <div class="card-body">
                        <div class="row">
                  <div class="col-md-4">
                    <div class="widget-small warning"><i class="icon fa fa-users fa-3x"></i>
                    <?php

// Ganti mysql_query dengan mysqli_query
$query = mysqli_query($connect, "SELECT count(*) AS totaluser FROM users");

if (!$query) {
    // Tangani error jika query gagal
    echo "Error: " . mysqli_error($connect);
    exit();
}

$datauser = mysqli_fetch_array($query);
?>
                      <div class="info">
                        <h4>Total Pengguna</h4>
                        <p> <b><?php echo $datauser['totaluser']; ?></b></p>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="widget-small info"><i class="icon fa fa-thumbs-o-up fa-3x"></i>
                      <?php
      								
// Ganti mysql_query dengan mysqli_query
$query = mysqli_query($connect, "SELECT count(*) AS totalencrypt FROM file WHERE status='1'");

if (!$query) {
    // Tangani error jika query gagal
    echo "Error: " . mysqli_error($connect);
    exit();
}

$dataencrypt = mysqli_fetch_array($query);
								      ?>
                      <div class="info">
                        <h4>Total Enkripsi</h4>
                        <p> <b><?php echo $dataencrypt['totalencrypt']; ?></b></p>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="widget-small danger"><i class="icon fa fa-files-o fa-3x"></i>
                      <div class="info">
                        <?php
        								// Ganti mysql_query dengan mysqli_query
$query = mysqli_query($connect, "SELECT count(*) AS totaldecrypt FROM file WHERE status='2'");

if (!$query) {
    // Tangani error jika query gagal
    echo "Error: " . mysqli_error($connect);
    exit();
}

// Ganti mysql_fetch_array dengan mysqli_fetch_array
$datadecrypt = mysqli_fetch_array($query);
  								      ?>
                        <h4>Total Dekripsi</h4>
                        <p> <b><?php echo $datadecrypt['totaldecrypt']; ?></b></p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row" >
          <div class="col-md-12" >
          <div class="card" style="background-color:rgb(255, 255, 255);">
            <div class="card-body">
          <center><img src="../assets/images/logo.png" alt="" class="img-responsive" width="250px">
        <h3>Selamat Datang di Dashboard</h3>
        <p style="color:#00000;">Dashboard Administrator</p>
        <p style="color:#000000;">Trans Studio Mini Majapahit Semarang</p>
        <p style="color:#00000;">Jl. Brigjen Sudiarto, Plamongan Sari, Kec. Pedurungan, Kota Semarang, Jawa Tengah, 50192</p>

      </center>
        
        </div>
      </div>
    </div>
        </div>
        
      </div>
    </div>
    <script src="../assets/js/jquery-2.1.4.min.js"></script>
    <script src="../assets/js/essential-plugins.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/pace.min.js"></script>
    <script src="../assets/js/main.js"></script>
  </body>
</html>
