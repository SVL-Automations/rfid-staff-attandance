<?php

//Include the database connection file
include "database.php";
date_default_timezone_set('Asia/Kolkata');

if (isset($_GET["rfid"])) {
	$rfid = $_GET["rfid"];
	$time = date("Y-m-d H:i:s");
	$date = date("Y-m-d");

	$data = mysqli_query($connection, "SELECT * FROM `staff` WHERE tag = '$rfid'");

	if (mysqli_num_rows($data) == 1) {
		$data = mysqli_fetch_assoc($data);
		$staffid = $data['id'];

		$i = mysqli_query($connection, "INSERT INTO `attendance`(`staffid`,`date`, `time`)		
				VALUES ('$staffid','$date','$time')");
		echo "Success";
	} else {
		echo "Fail";
	}
}

// SELECT staffid, min(time) as InTime, max(time) as OutTime FROM attendance GROUP BY staffid, date;
