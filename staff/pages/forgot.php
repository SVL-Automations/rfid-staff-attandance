<?php
session_start();
ob_start();
include("../../database.php");
include("../../mail/mail.php");
$msg;
$color;

if (isset($_POST['submit'])) {
  $username = mysqli_real_escape_string($connection, $_POST['email']);
  $mobile = mysqli_real_escape_string($connection, $_POST['mobile']);


  $res = mysqli_query($connection, "select * from staff where mobile = '$mobile' and email = '$username' and status='1'");
  if (mysqli_num_rows($res) == 1) {
    $password = substr(str_shuffle("0123456789"), 0, 2) .
      substr(str_shuffle("abcdefghijkmnpqrstuvwxyz"), 0, 3) .
      substr(str_shuffle("ABCDEFGHJKLMNPQRSTUVWXYZ"), 0, 3);
    $encpassword = md5($password);

    $body =  "Dear " . $username . "  ,  <br/>            
          Your Account Password is reset successfully at our " . $project . " Account. 
          <br/> We thank you for connecting with us.<br/><br/>

          Your  New Password is : " . $password . "<br/><br/>
          We request you to keep your login information confidential.<br/><br/>
                    
          
          Regards,<br/>
          " . $project . "           
          ";

    $subject = "Password reset to " . $project . " Account";

    $mailstatus = mailsend($username, $body, $subject, $project);

    if ($mailstatus == 'Success') {
      $data = mysqli_query($connection, "update staff set password = '$encpassword' where email = '$username'");
      $msg = "Please check email address for new Password.";
      $color = "alert-success";
    } else {
      $msg = "Please Try after sometime!!!";
      $color = "alert-danger";
    }
  } else {
    $msg = "Please Enter Correct Username and Email.";
    $color = "alert-danger";
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title> <?= $project ?> : Forget Password</title>
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
  <!-- iCheck -->
  <link rel="stylesheet" href="../../plugins/iCheck/square/blue.css">

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
      <a href="../"><b> <?= $project ?> </b></a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
      <?php
      if (isset($msg)) {
        echo  '<div class="alert ' . $color . '">
                      <ul class="margin-bottom-none padding-left-lg">
                        <li>' . $msg . '</li>                      
                      </ul>                								
                </div>';
      }
      ?>
      <p class="login-box-msg">Staff Forget Password </p>

      <form action="" method="post">
        <div class="form-group has-feedback">
          <input type="number" class="form-control" placeholder="Enter Mobile" name="mobile" required min="2000000000">
          <span class="fa fa-user form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
          <input type="email" class="form-control" placeholder="Enter Email Address" name="email" required>
          <span class="fa fa-envelope form-control-feedback"></span>
        </div>

        <button type="submit" class="btn btn-lg btn-success btn-block " name="submit">Submit</button>
        <a class="btn btn-lg btn-warning btn-block" href="../">Sign In</a>

      </form>
    </div>
    <!-- /.login-box-body -->
  </div>
  <!-- /.login-box -->

  <!-- jQuery 3 -->
  <script src="../../bower_components/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap 3.3.7 -->
  <script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <!-- iCheck -->
  <script src="../../plugins/iCheck/icheck.min.js"></script>
  <script>
    $(function() {
      $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' /* optional */
      });
    });
  </script>
</body>

</html>