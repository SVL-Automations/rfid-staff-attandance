<?php 	    

    define ('hostname',"localhost");	 // Server Name or host Name 
    define ('username','root'); // database Username 
    define ('password',''); // database Password 
    define ('database','staffattendance'); // database Name 

    $project = "RFID Based Staff Attandance";
    $shortname = "YCP";
    $slogan = "Stay safe Stay healthy";
    $officename = " YCP ";
    $officename1 = "YCP";
    $skincolor = 'skin-yellow';
    global $connection;
    $connection = @mysqli_connect(hostname,username,password,database) or die('Connection could not be made to the SQL Server. Please contact report this system error at <font color="blue">8888888888</font>');
?>
