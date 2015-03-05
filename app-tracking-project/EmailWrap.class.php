<?php require 'plugins/PHPMailer-master/PHPMailerAutoload.php'; 

// Wrapper to send confirmation emails.
class EmailWrap
{
	private $email;

	public static function sendEmail($recipient, $subject, $body)
	{
		$email = new PHPMailer;
		$email->isSMTP();
		$email->Port = 26;
		$email->Host = 'box1084.bluehost.com;';
		$email->SMTPAuth = true;
		$email->Username = 'noreply@jasonmajors.net';
		$email->Password = '3Nb*jRo@DX5email';
		$email->SMTPSecure = 'tls';
		$email->FromName = 'Jason Majors';
		$email->WordWrap = 50;
		$email->isHTML(true);
		$email->addAddress($recipient);
		$email->Subject = $subject;
		$email->Body = $body;

		if(!$email->send()) {
			echo 'Message could not be sent.';
			echo 'Mailer Error: ' . $email->ErrorInfo;
		} 	else {
			echo "Message Sent!";
		}
	}
}