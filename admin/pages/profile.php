<?php
include("sessioncheck.php");
$username = $_SESSION['VALID_RFID_ADMIN'];

//Get profile details
if (isset($_POST['getprofile'])) {
  $result1 = array();
  $result = mysqli_query($connection, "SET NAMES utf8mb4");
  $result = mysqli_query($connection, "Select * from `admin` where `email` = '$username'");
  $result1[] = mysqli_fetch_all($result, MYSQLI_ASSOC);

  echo json_encode($result1);
  exit();
}

//Update profile details
if (isset($_POST['changeprofile'])) {

  $name = mysqli_real_escape_string($connection, trim(strip_tags($_POST['name'])));
  $mobile = mysqli_real_escape_string($connection, trim(strip_tags($_POST['mobile'])));

  $msg = new \stdClass();

  $profileupdate = mysqli_query($connection, "UPDATE `admin` SET 
                                              `name`='$name', `mobile`='$mobile'
                                               WHERE `email` = '$username'
                                              ");

  if ($profileupdate > 0) {
    $msg->value = 1;
    $msg->data = "Profile Update Successfully.";
    $msg->type = "alert alert-success alert-dismissible ";
    $_SESSION['name'] = $name;
  } else {
    $msg->value = 0;
    $msg->data = "Profile Not update. Please Try Again.";
    $msg->type = "alert alert-danger alert-dismissible ";
  }
  echo json_encode($msg);
  exit;
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= $project ?> : Admin Profile Update</title>
  <link rel="icon" href="../../dist/img/small.png" type="image/x-icon">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="../../bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="../../bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../../dist/css/skins/_all-skins.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="hold-transition <?= $skincolor ?> layout-top-nav">
  <!-- Site wrapper -->
  <div class="wrapper">

    <?php include("header.php"); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <div class="container">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h4>
            <?= $project ?>
            <small><?= $slogan ?></small>
          </h4>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Admin</a></li>
            <li class="active">Profile Update</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <!-- left column -->
            <div class="col-md-6">
              <!-- general form elements -->
              <div class="box box-warning">
                <div class="box-header with-border">
                  <h3 class="box-title">Admin Profile Update</h3>
                </div>
                <div class="alert " id="alertclass" style="display: none">
                  <button type="button" class="close" onclick="$('#alertclass').hide()">×</button>
                  <p id="msg"></p>
                </div>

                <!-- /.box-header -->
                <!-- form start -->
                <form role="form" id="changesuperprofile" action="" method="post">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="exampleInputEmail1">Username</label>
                      <input type="text" class="form-control" id="username" placeholder="Enter Username" required name="username" readonly>
                    </div>

                    <div class="form-group">
                      <label for="exampleInputEmail1">Enter Name</label>
                      <input type="text" class="form-control" id="name" placeholder="Enter First Name" required name="name" pattern="[A-Za-z\s]+">
                    </div>

                    <div class="form-group">
                      <label for="exampleInputPassword1"> Mobile Number </label>
                      <input type="number" name="mobile" class="form-control" id="mobile" placeholder="Enter Mobile Number" required min="2000000000" max="9999999999" pattern="[0-9]{10}">
                    </div>

                    <div class="box-footer">
                      <input type="hidden" name="changeprofile" id="changeprofile" value="changeprofile">
                      <button type="submit" class="btn btn-success" name="submit">Update</button>
                      <button type="reset" class="btn btn-warning">Reset</button>
                    </div>
                </form>
              </div>
            </div>
          </div>
        </section>
        <!-- /.content -->
      </div>
      <!-- /.content-wrapper -->
    </div>
    <?php include("footer.php"); ?>

  </div>
  <!-- ./wrapper -->

  <!-- jQuery 3 -->
  <script src="../../bower_components/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap 3.3.7 -->
  <script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <!-- SlimScroll -->
  <script src="../../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
  <!-- FastClick -->
  <script src="../../bower_components/fastclick/lib/fastclick.js"></script>
  <!-- AdminLTE App -->
  <script src="../../dist/js/adminlte.min.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="../../dist/js/demo.js"></script>
  <script>
    $(document).ready(function() {
      $('.sidebar-menu').tree()

      $.ajax({
        url: $(location).attr('href'),
        type: 'POST',
        data: {
          "getprofile": "getprofile"
        },
        success: function(response) {
          var returnedData = JSON.parse(response);
          $("#username").attr("value", returnedData[0][0]['email']);
          $("#name").attr("value", returnedData[0][0]['name']);
          $("#mobile").attr("value", returnedData[0][0]['mobile']);
        }
      });

      $("#changesuperprofile").submit(function(e) {
        $('#alertclass').removeClass();
        $('#msg').empty();
        e.preventDefault();

        $.ajax({
          url: $(location).attr('href'),
          type: 'POST',
          data: $("#changesuperprofile").serialize(),
          success: function(response) {
            var returnedData = JSON.parse(response);
            if (returnedData['value'] == 1) {
              $('#alertclass').addClass(returnedData['type']);
              $('#msg').append(returnedData['data']);
              $("#alertclass").show();
            } else {
              $('#alertclass').addClass(returnedData['type']);
              $('#msg').append(returnedData['data']);
              $("#alertclass").show();
            }
          }
        });
      });
    })
  </script>
</body>

</html>