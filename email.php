<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);
$sender = "limsinkuan0415@e.newera.edu.my";
$password = "ttjaopefuabjmqsc";
try {
	for ($i = 0; $i < $recipient_loop; $i++) {
		//remove sender and password field if using hardcode.

		$mail->isSMTP();
		$mail->Host	 = "smtp.gmail.com";
		$mail->SMTPAuth = true;
		$mail->Username = "limsinkuan0415@e.newera.edu.my";	//sender and password can be hardcode. example: 'example@gmail.com'.
		$mail->Password = "ttjaopefuabjmqsc"; //password from app password of your sender email. example: 'yourpassword'. https://support.google.com/mail/answer/185833?hl=en
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
		$mail->Port	 = 465;

		$mail->setFrom($sender);
		$mail->addAddress($receipt);

		$mail->isHTML(true);
		$mail->Subject = $subject;
		$mail->Body = $body;
		$mail->AltBody = $body;
	}
	if ($mail->send()) {
		echo "<div class='alert alert-success'>Mail has been sent successfully!</div>";
	} else {
		echo "Failed to send email.";
	}
} catch (Exception $e) {
	echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
