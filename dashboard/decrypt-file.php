<?php
session_start();
include('../config.php');

// Check if user is logged in
if (empty($_SESSION['username'])) {
    header("location:../index.php");
    exit();
}

// Update user's last activity timestamp
$last = $_SESSION['username'];
$sqlupdate = "UPDATE users SET last_activity=NOW() WHERE username=?";
$stmt = $connect->prepare($sqlupdate); 
$stmt->bind_param("s", $last);
$stmt->execute();
$stmt->close();

// Siapkan query dengan parameter placeholder (?)
$query = mysqli_prepare($connect, "SELECT fullname, job_title, last_activity FROM users WHERE username=?");

// Periksa apakah query berhasil disiapkan
if ($query === false) {
    die('Error preparing statement: ' . mysqli_error($connect));
}

// Bind parameter untuk query (username) menggunakan 's' untuk string
mysqli_stmt_bind_param($query, "s", $_SESSION['username']);

// Eksekusi query
mysqli_stmt_execute($query);

// Ambil hasilnya
$result = mysqli_stmt_get_result($query);

// Ambil data dari hasil query
$data = mysqli_fetch_array($result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html>
  <head>
    <title><?php echo htmlspecialchars($data['fullname']); ?> - AES-128</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../assets/css/main.css">
    <link rel="stylesheet" type="text/css" href="../assets/plugins/datatables/css/jquery.dataTables.css">
  </head>
  <body class="sidebar-mini fixed">
    <?php include('navmenu.php'); ?>
    <div class="content-wrapper">
      <div class="page-title">
        <h1><i class="fa fa-file"></i> Dekripsi Berkas</h1>
        <ul class="breadcrumb">
          <li><i class="fa fa-home fa-lg"></i></li>
          <li><a href="index.php">Dashboard</a></li>
          <li>Dekripsi Berkas</li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <?php
              // Securely fetch file data from the database
$id_file = isset($_GET['id_file']) ? (int)$_GET['id_file'] : 0;
if ($id_file > 0) {
    // Ganti $mysqli dengan $connect
    $query = $connect->prepare("SELECT * FROM file WHERE id_file=?");
    $query->bind_param("i", $id_file);
    $query->execute();
    $result = $query->get_result();
    $data2 = $result->fetch_assoc();
    $query->close();
}

              ?>
              <h3 align="center">Dekripsi Berkas <i style="color:blue"><?php echo htmlspecialchars($data2['file_name_finish']); ?></i></h3><br>
              <form class="form-horizontal" method="post" action="decrypt-process.php">
                <div class="table-responsive">
                  <table class="table striped">
                    <tr>
                      <td>Nama Sumber Berkas</td>
                      <td>:</td>
                      <td><?php echo htmlspecialchars($data2['file_name_source']); ?></td>
                    </tr>
                    <tr>
                      <td>Nama Berkas Enkripsi</td>
                      <td>:</td>
                      <td><?php echo htmlspecialchars($data2['file_name_finish']); ?></td>
                    </tr>
                    <tr>
                      <td>Ukuran Berkas</td>
                      <td>:</td>
                      <td><?php echo htmlspecialchars($data2['file_size']); ?> KB</td>
                    </tr>
                    <tr>
                      <td>Tanggal Enkripsi</td>
                      <td>:</td>
                      <td><?php echo htmlspecialchars($data2['tgl_upload']); ?></td>
                    </tr>
                    <tr>
                      <td>Keterangan</td>
                      <td>:</td>
                      <td><?php echo htmlspecialchars($data2['keterangan']); ?></td>
                    </tr>
                    <tr>
                      <td>Masukkan Password Berkas Untuk Mendekripsi</td>
                      <td></td>
                      <td>
                        <div class="col-md-6">
                          <input type="hidden" name="fileid" value="<?php echo htmlspecialchars($data2['id_file']); ?>">
                          <input class="form-control" id="inputPassword" type="password" placeholder="Password" name="pwdfile" required><br>
                          <input type="submit" name="decrypt_now" value="Dekripsi Berkas" class="form-control btn btn-primary">
                        </div>
                      </td>
                    </tr>
                  </table>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="../assets/js/jquery-2.1.4.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function() {
        $('#file').dataTable({
          "bPaginate": true,
          "bLengthChange": false,
          "bFilter": true,
          "bInfo": true,
          "bAutoWidth": true,
          "order": [0, "asc"]
        });
      });
    </script>
    <script src="../assets/js/essential-plugins.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/plugins/datatables/js/jquery.dataTables.js"></script>
    <script src="../assets/js/plugins/pace.min.js"></script>
    <script src="../assets/js/main.js"></script>
  </body>
</html>
