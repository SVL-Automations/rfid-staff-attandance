<?php

include("sessioncheck.php");
date_default_timezone_set('Asia/Kolkata');

$userid = $_SESSION['userid'];

if (isset($_POST['tabledata'])) {

    $data = new \stdClass();
    $result = mysqli_query($connection, "SET NAMES utf8mb4");
    $result = mysqli_query($connection, "SELECT * from staff");
    $data->list = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $result = mysqli_query($connection, "SELECT * from department WHERE status = '1'");
    $data->department = mysqli_fetch_all($result, MYSQLI_ASSOC);

    echo json_encode($data);
    exit();
}

//Update faculty User Status
if (isset($_POST['Update'])) {
    $msg = new \stdClass();
    $result = mysqli_query($connection, "SET NAMES utf8mb4");

    $userid = mysqli_real_escape_string($connection, trim(strip_tags($_POST['userid'])));
    $status = mysqli_real_escape_string($connection, trim(strip_tags($_POST['status'])));
    $newstatus;

    if ($status == 1) {
        $newstatus = 0;
    } else {
        $newstatus = 1;
    }

    if (mysqli_query($connection, "Update staff set status = '$newstatus' where id = '$userid'") <= 0) {
        $msg->value = 0;
        $msg->data = " You cannot update staff. Please Try again.";
        $msg->type = "alert alert-danger alert-dismissible ";
    } else {

        $msg->value = 1;
        $msg->data = "Staff Update Successfully.";
        $msg->type = "alert alert-success alert-dismissible ";
    }

    echo json_encode($msg);
    exit();
}

//Add staff User 
if (isset($_POST['Add'])) {
    $msg = new \stdClass();
    $result = mysqli_query($connection, "SET NAMES utf8mb4");
    $email1 = array();

    $name = mysqli_real_escape_string($connection, trim(strip_tags($_POST["name"])));
    $ptext = substr(str_shuffle("0123456789"), 0, 2) .
        substr(str_shuffle("abcdefghijkmnpqrstuvwxyz"), 0, 3) .
        substr(str_shuffle("ABCDEFGHJKLMNPQRSTUVWXYZ"), 0, 3);
    $password = md5($ptext);
    $email = mysqli_real_escape_string($connection, trim(strip_tags($_POST["email"])));
    $email1[] = $email;
    $mob = mysqli_real_escape_string($connection, trim(strip_tags($_POST["mobile"])));
    $department = mysqli_real_escape_string($connection, trim(strip_tags($_POST["department"])));
    $tag = mysqli_real_escape_string($connection, trim(strip_tags($_POST["tag"])));

    if (mysqli_query($connection, "INSERT INTO `staff`
                                    (`name`, `password`, `email`, `mobile`,`tag`, `status`,`deptid`)
                                    values
                                    ('$name', '$password','$email','$mob','$tag','1','$department')")) {

        $body =  "Dear " . $name . "  ,  <br/>

        Your new account is Created at " . $project . " as staff User. 
        <br/> Welcome you to the <b>" . $project . "</b>.We thank you for join with us.<br/><br/>
      
        Your login ID is :" . $email . "<br/>
        Your Password is : " . $ptext . "<br/><br/>
        
        We request you to keep your login information confidential.<br/><br/>
        Thanks for Showing interest in our company.<br/><br/><br/>
        
        Regards,<br/>
        " . $project . "     
        ";

        $subject = "Staff User Account Created at " . $project;
        $mailstatus = mailsend($email, $body, $subject, $project);
        $mailmsg = "";
        if ($mailstatus == "Fail") {
            $mailmsg = "Mail Sending Fail.";
        } else {
            $mailmsg = "Mail Sending Successfully.";
        }

        $msg->value = 1;
        $msg->data = "Staff User Added Successfully. " . $mailmsg;
        $msg->type = "alert alert-success alert-dismissible ";
    } else {
        $msg->value = 0;
        $msg->data = "Please Check Information. or Username Already Exists.";
        $msg->type = "alert alert-danger alert-dismissible ";
    }

    echo json_encode($msg);
    exit();
}

//Edit staff User 
if (isset($_POST['Edit'])) {
    $msg = new \stdClass();
    $result = mysqli_query($connection, "SET NAMES utf8mb4");

    $edittag = mysqli_real_escape_string($connection, trim(strip_tags($_POST["edittag"])));
    $editid = mysqli_real_escape_string($connection, trim(strip_tags($_POST["editid"])));

    if (mysqli_query($connection, "UPDATE `staff` SET `tag` = '$edittag' WHERE `id` = '$editid'") > 0) {
        $msg->value = 1;
        $msg->data = "Staff User Update Successfully. ";
        $msg->type = "alert alert-success alert-dismissible ";
    } else {
        $msg->value = 0;
        $msg->data = "You cannot update User. Please Try again.";
        $msg->type = "alert alert-danger alert-dismissible ";
    }

    echo json_encode($msg);
    exit();
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $project ?> : Staff User</title>
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
    <link rel="stylesheet" href="../../dist/css/adminLTE.min.css">
    <!-- adminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="../../dist/css/skins/_all-skins.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../../bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="../../bower_components/select2/dist/css/select2.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <style>
        tfoot input {
            width: 50%;
            padding: 3px;
            box-sizing: border-box;
        }
    </style>
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
                        <li class="active">Staff Users</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Default box -->
                            <div class="box box-warning">
                                <div class="box-header with-border">
                                    <h3 class="box-title"> Staff Registration</h3>
                                    <a class="btn btn-social-icon btn-warning pull-right" style="margin:5px" title="Add New User" data-toggle="modal" data-target="#modaladdstaff"><i class="fa fa-plus"></i></a>
                                </div>
                                <div class="alert " id="alertclass" style="display: none">
                                    <button type="button" class="close" onclick="$('#alertclass').hide()">×</button>
                                    <p id="msg"></p>
                                </div>
                                <!-- /.box-header -->
                                <!-- form start -->
                                <div class="box-body  table-responsive">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th class='text-center'>Id</th>
                                                <th class='text-center'>Name</th>
                                                <th class='text-center'>Email</th>
                                                <th class='text-center'>Mobile</th>
                                                <th class='text-center'>Tag</th>
                                                <th class='text-center'>Status</th>
                                                <th class='text-center'>Update</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody">

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th class='text-center'>Id</th>
                                                <th class='text-center'>Name</th>
                                                <th class='text-center'>Email</th>
                                                <th class='text-center'>Mobile</th>
                                                <th class='text-center'>Tag</th>
                                                <th class='text-center'>Status</th>
                                                <th class='text-center'>Update</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <!-- /.box-body -->
                            <!-- /.box-footer-->
                        </div>
                    </div>
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->
        </div>
        <!-- Add staff User modal -->
        <form id="addstaff" action="" method="post">
            <div class="modal fade" id="modaladdstaff" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-green">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span></button>
                            <h4 class="modal-title">Add New Staff </h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert " id="addalertclass" style="display: none">
                                <button type="button" class="close" onclick="$('#addalertclass').hide()">×</button>
                                <p id="addmsg"></p>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Enter Name</label>
                                <input type="text" class="form-control" placeholder="Enter Name" name="name" id="name" required pattern="[a-zA-Z\s]+">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Mobile</label>
                                <input type="number" class="form-control" placeholder="Enter Mobile" name="mobile" id="mobile" min="2000000000" required pattern="[0-9]{10}">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Email address</label>
                                <input type="email" class="form-control" placeholder="Enter email" name="email" id="email" required>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Enter Tag</label>
                                <input type="text" class="form-control" placeholder="Enter Tag" name="tag" id="tag" required pattern="[a-zA-Z0-9\s]+">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputPassword1">Department </label>
                                <select class="form-control select2 select3" style="width: 100%;" required name="department" id="department">
                                </select>
                            </div>


                        </div>
                        <div class="modal-footer ">
                            <input type="hidden" name="Add" value="Add">
                            <button type="submit" name="Add" value="Add" id='add' class="btn btn-success">Register Me</button>
                            <button type="button" class="btn pull-right btn-warning" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->

            </div>
        </form>
        <!-- End Add staff user modal -->

        <!-- Edit staff User modal -->
        <form id="editstaff" action="" method="post">
            <div class="modal fade" id="modaleditstaff" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-red">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span></button>
                            <h4 class="modal-title">Edit staff User</h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert " id="editalertclass" style="display: none">
                                <button type="button" class="close" onclick="$('#editalertclass').hide()">×</button>
                                <p id="editmsg"></p>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Email address</label>
                                <input type="email" class="form-control" placeholder="Enter email" name="editemail" id="editemail" required readonly>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Enter Tag</label>
                                <input type="text" class="form-control" placeholder="Enter Tag" name="edittag" id="edittag" required pattern="[a-zA-Z0-9\s]+">
                            </div>

                        </div>
                        <div class="modal-footer ">
                            <input type="hidden" name="editid" id='editid'>
                            <input type="hidden" name="Edit" value="Edit">
                            <button type="submit" name="Edit" value="Edit" id='Edit' class="btn btn-success">Edit Me</button>
                            <button type="button" class="btn pull-right btn-warning" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->

            </div>
        </form>
        <!-- End Add user modal -->


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
    <!-- adminLTE App -->
    <script src="../../dist/js/adminlte.min.js"></script>
    <!-- adminLTE for demo purposes -->
    <script src="../../dist/js/demo.js"></script>
    <!-- DataTables -->
    <script src="../../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="../../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <!-- Select2 -->
    <script src="../../bower_components/select2/dist/js/select2.full.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.sidebar-menu').tree()

            $('[data-toggle="tooltip"]').tooltip();

            $('#example1').DataTable({
                stateSave: true,
                destroy: true,
            });

            //Initialize Select2 Elements
            $('.select2').select2()

            //display data table   
            function tabledata() {
                $('.select3').empty();
                $('#example1').dataTable().fnDestroy();
                $('#example1 tbody').empty();
                $('#addstaff')[0].reset();

                $.ajax({
                    url: $(location).attr('href'),
                    type: 'POST',
                    data: {
                        'tabledata': 'tabledata'
                    },
                    success: function(response) {
                        //console.log(response);
                        var returnedData = JSON.parse(response);
                        var srno = 0;
                        $.each(returnedData['list'], function(key, value) {
                            srno++;
                            var update = "";
                            var datatext = 'data-editid="' + value.id + '" data-status="' + value.status +
                                '" data-name="' + value.name + '" data-tag="' + value.tag + '"';

                            var editbutton = '<button type="submit" name="Edit" id="edit" ' +
                                datatext +
                                ' class="btn btn-xs btn-warning edit-button" style= "margin:5px" title="Edit Staff User" data-toggle="modal" data-target="#modaleditstaff"><i class="fa fa-edit"></i></button>';


                            if (value.status == 1) {
                                update = '<button type="submit" name="Update" id="Update" ' +
                                    'data-editid="' + value.id + '" data-status="' + value.status +
                                    '" class="btn btn-xs btn-danger update-button" style= "margin:5px" title="Deactivate Staff User" ><i class="fa fa-close"></i></button>';
                            } else {
                                update = '<button type="submit" name="Update" id="Update" ' +
                                    'data-editid="' + value.id + '" data-status="' + value.status +
                                    '" class="btn btn-xs btn-success update-button" style= "margin:5px" title="Activate Staff User" ><i class="fa fa-check"></i></button>';
                            }

                            var html = '<tr class="odd gradeX">' +
                                '<td class="text-center">' + srno + '</td>' +
                                '<td class="text-center">' + value.name + ' </td>' +
                                '<td class="text-center">' + value.email + '</td>' +
                                '<td class="text-center">' + value.mobile + '</td>' +
                                '<td class="text-center">' + value.tag + '</td>' +
                                '<td class="text-center">' + value.status + '</td>' +
                                '<td class="text-center">' + editbutton + update + '</td>' +
                                '</tr>';
                            $('#example1 tbody').append(html);
                        });

                        $('.select3').append(new Option("Select department", ""));
                        $.each(returnedData['department'], function(key, value) {
                            $('.select3').append(new Option(value.name, value.id));
                        });

                        $('.select2').select2()

                        $('#example1').DataTable({
                            stateSave: true,
                            destroy: true,
                        });
                    }
                });
            }

            tabledata();

            $('#addstaff').submit(function(e) {

                $('#example1').dataTable().fnDestroy();
                $('#example1 tbody').empty();

                $('#addalertclass').removeClass();
                $('#addmsg').empty();

                e.preventDefault();

                $.ajax({
                    url: $(location).attr('href'),
                    type: 'POST',
                    data: $('#addstaff').serialize(),
                    success: function(response) {
                        // console.log(response);
                        returnedData = JSON.parse(response);
                        if (returnedData['value'] == 1) {
                            $('#addstaff')[0].reset();
                            $('#addalertclass').addClass(returnedData['type']);
                            $('#addmsg').append(returnedData['data']);
                            $("#addalertclass").show();
                            tabledata();
                        } else {
                            $('#addalertclass').addClass(returnedData['type']);
                            $('#addmsg').append(returnedData['data']);
                            $("#addalertclass").show();
                            tabledata();
                        }

                    }
                });

            });

            //Update user status
            $(document).on("click", ".update-button", function(e) {

                $('#alertclass').removeClass();
                $('#msg').empty();

                e.preventDefault();
                $.ajax({
                    url: $(location).attr('href'),
                    type: 'POST',
                    data: {
                        'Update': 'Update',
                        'userid': $(this).data('editid'),
                        'status': $(this).data('status')
                    },
                    success: function(response) {
                        //console.log(response);
                        returnedData = JSON.parse(response);
                        if (returnedData['value'] == 1) {
                            $('#alertclass').addClass(returnedData['type']);
                            $('#msg').append(returnedData['data']);
                            $("#alertclass").show();
                            tabledata();
                        } else {
                            $('#alertclass').addClass(returnedData['type']);
                            $('#msg').append(returnedData['data']);
                            $("#alertclass").show();
                        }
                        tabledata();
                    }
                });
            });

            //Edit staff  to modal
            $(document).on("click", ".edit-button", function(e) {
                $('#editalertclass').removeClass();
                $('#editmsg').empty();
                $(".modal-body #edittag").val($(this).data('tag'));
                $("#editid").val($(this).data('editid'));
            });

            //Edit staff
            $('#editstaff').submit(function(e) {
                $('#editalertclass').removeClass();
                $('#editmsg').empty();
                e.preventDefault();

                $.ajax({
                    url: $(location).attr('href'),
                    type: 'POST',
                    data: $('#editstaff').serialize(),
                    success: function(response) {
                        // console.log(response);
                        returnedData = JSON.parse(response);
                        if (returnedData['value'] == 1) {
                            $('#example1').dataTable().fnDestroy();
                            $('#example1 tbody').empty();

                            $('#editalertclass').addClass(returnedData['type']);
                            $('#editmsg').append(returnedData['data']);
                            $("#editalertclass").show();
                            tabledata();
                        } else {
                            $('#editalertclass').addClass(returnedData['type']);
                            $('#editmsg').append(returnedData['data']);
                            $("#editalertclass").show();
                        }
                    }
                });
            });
        })
    </script>
</body>

</html>