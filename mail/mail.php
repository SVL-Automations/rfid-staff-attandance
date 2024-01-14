<?php
require('PHPMailer.php');
require('SMTP.php');
require('Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


function mailsend($email, $body, $subject, $project, $filenames = NULL)
{
	$mail = new PHPMailer();

	$mail->IsSMTP();                                      	// set mailer to use SMTP
	$mail->CharSet = 'UTF-8';
	$mail->Host = "smtp.hostinger.in";  					// specify main and backup server
	$mail->SMTPAuth = true;    	 							// turn on SMTP authentication
	$mail->Username = "attendance@svlpro.online";  	// SMTP username
	$mail->Password = "password";						// SMTP password
	$mail->Port = 587;
	$mail->From = "attendance@svlpro.online";
	$mail->FromName = $project;
	$mail->addReplyTo("attendance@svlpro.online");
	
	foreach ((array) $email as $e) {
		
		$mail->AddAddress($e);
	}

	if (is_array($filenames) || isset($filenames)) {
		foreach ($filenames as $file) {
			$mail->addAttachment($file);
		}
	}
	$mail->WordWrap = 50;                                 // set word wrap to 50 character
	$mail->IsHTML(true);                                  // set email format to HTML

	$mail->Subject = $subject;
	$mail->Body    = $body;
	$mail->AltBody = "This is the body in plain text for non-HTML mail clients";

	if (!$mail->Send()) {
		return "Fail";
	} else {
		return "Success";
	}
}
