<?php

session_start();
ob_start();

include("../../database.php");
include("../../mail/mail.php");

if (!isset($_SESSION['VALID_RFID_ADMIN'])) 
{
  header("location:logout.php");
}



?>