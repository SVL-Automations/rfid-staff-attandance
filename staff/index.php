<?php

include("../database.php");
$msg;
$color;
session_start();
ob_start();

if (isset($_SESSION['VALID_RFID_STAFF'])) {
  header("location:pages/");
}


if (isset($_POST['login'])) {
  if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = trim(strip_tags($_POST['username']));
    $password = trim(strip_tags($_POST['password']));
    $encpassword = md5($password);
    $msg = new \stdClass();
    $result = mysqli_query($connection, "SET NAMES utf8mb4");
    $result = mysqli_query($connection, "select * from staff  
                                          where `email` = '" . mysqli_real_escape_string($connection, $username) . "' 
                                          AND `password` = '" . mysqli_real_escape_string($connection, $encpassword) . "'                                           
                                        ");

    if (mysqli_num_rows($result) == 1) {
      $login_data = mysqli_fetch_assoc($result);
      $_SESSION['name'] = $login_data['name'];
      $_SESSION['VALID_RFID_STAFF'] = $username;
      $_SESSION['userid'] = $login_data['id'];
      $msg->type = "alert alert-success alert-dismissible ";
      $msg->data = "Login Successful.";
      echo json_encode($msg);
    } else {
      $msg->type = "alert alert-danger alert-dismissible ";
      $msg->data = "Please Enter Correct Username and Password.";
      echo json_encode($msg);
    }
  }
  exit();
}

?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= $project ?> <?= $officename ?></title>
  <link rel="icon" href="../dist/img/small.png" type="image/x-icon">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="../bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="../plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

</head>

<body class="hold-transition login-page">
  <div class="login-box">

    <div class="login-logo">
      <a href="../"><b><?= $project ?> </b><br>Staff Login</a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
      <div class="alert " id="alertclass" style="display: none">
        <button type="button" class="close" onclick="$('#alertclass').hide()">×</button>
        <p id="msg"></p>
      </div>
      <p class="login-box-msg">Sign in to start your session</p>

      <form action="" method="post" id="loginform">
        <input type="hidden" name="login" value="login" id="login">
        <div class="form-group has-feedback">
          <input type="email" class="form-control" placeholder="Enter Username" name="username" required id="username">
          <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
          <input type="password" class="form-control" placeholder="Enter Password" name="password" required id="password">
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>

        <button type="submit" class="btn btn-lg btn-success btn-block " name="submit" id="submit">Sign In</button>
        <a class="btn btn-lg btn-warning btn-block" href="pages/forgot.php">Forget Password</a>
      </form>

    </div>
    <!-- /.login-box-body -->
  </div>
  <!-- /.login-box -->

  <!-- jQuery 3 -->
  <script src="../bower_components/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap 3.3.7 -->
  <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <!-- iCheck -->
  <script src="../plugins/iCheck/icheck.min.js"></script>
  <script>
    $(function() {

      $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' /* optional */
      });

      $('#loginform').submit(function(e) {

        $('#alertclass').removeClass();
        $('#msg').empty();

        e.preventDefault();

        $.ajax({
          url: $(location).attr('href'),
          type: 'POST',
          data: $('#loginform').serialize(),
          success: function(response) {
            //console.log(response);

            var returnedData = JSON.parse(response);

            if (returnedData['data'] == "Login Successful.") {
              window.location.href = 'pages/';
            } else {
              $('#alertclass').addClass(returnedData['type']);
              $('#msg').append(returnedData['data']);
              $("#alertclass").show();
            }
          }
        });

      });
    });
  </script>
</body>

</html>