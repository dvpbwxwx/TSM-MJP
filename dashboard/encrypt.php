<?php
session_start();
include('../config.php');

// Pastikan koneksi sudah dibuat dengan benar, misalnya:
$connect = mysqli_connect('localhost', 'root1', '', 'aes', 8111);
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

if (empty($_SESSION['username'])) {
    header("location:../index.php");
}

$last = $_SESSION['username'];
$sqlupdate = "UPDATE users SET last_activity=now() WHERE username='$last'";

// Ganti mysql_query dengan mysqli_query
$queryupdate = mysqli_query($connect, $sqlupdate);

if (!$queryupdate) {
    // Tangani error jika query gagal
    echo "Error: " . mysqli_error($connect);
    exit();
}
?>

<!DOCTYPE html>
<html>
<?php
$user = $_SESSION['username'];
$query = mysqli_query($connect, "SELECT fullname, job_title, last_activity FROM users WHERE username='$user'");

// Pastikan query berhasil dan data ada
if ($query) {
    $data = mysqli_fetch_array($query);
} else {
    // Tangani error jika query gagal
    echo "Error: " . mysqli_error($connect);
    exit();
}
?>
  <head>
    <title> <?php echo $data['fullname']; ?> - AES-128</title>
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
            <h1><i class="fa fa-file"></i> Enkripsi Berkas</h1>
          </div>
          <div>
            <ul class="breadcrumb">
              <li><i class="fa fa-home fa-lg"></i></li>
              <li><a href="index.php">Dashboard</a></li>
              <li>Enkripsi Berkas</li>
            </ul>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <form class="form-horizontal" method="post" action="encrypt-process.php" enctype="multipart/form-data">
                      <fieldset>
                        <legend>Enkripsi</legend>
                        <div class="form-group">
                          <label class="col-lg-2 control-label" for="inputPassword">Tanggal</label>
                          <div class="col-lg-4">
                            <input class="form-control" id="inputTgl" type="text" placeholder="Tanggal" name="datenow" value="<?php echo date("Y-m-d");?>" readonly>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-lg-2 control-label" for="inputFile">Berkas</label>
                          <div class="col-lg-4">
                            <input class="form-control" id="inputFile" placeholder="Input File" type="file" name="file" required>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-lg-2 control-label" for="inputPassword">Password</label>
                          <div class="col-lg-4">
                            <input class="form-control" id="inputPassword" type="password" placeholder="Password enkripsi berkas" name="pwdfile" required>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-lg-2 control-label" for="textArea">Keterangan</label>
                          <div class="col-lg-4">
                            <textarea class="form-control" id="textArea" rows="3" name="desc" placeholder="Keterangan berkas"></textarea>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-lg-2 control-label" for="textArea"></label>
                          <div class="col-lg-2">
                            <input type="submit" name="encrypt_now" value="Enkripsi Berkas" class="form-control btn btn-primary">
                          </div>
                        </div>
                      </fieldset>
                    </form>
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
