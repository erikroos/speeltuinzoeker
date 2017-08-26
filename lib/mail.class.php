<?php
class Mail {
	public static function sendMail($to, $subject, $message) {
		$message = "<html><head><title>" . $subject . "</title></head>" . "<body style=\"font-family: Arial;\">" . $message . "</body>";
		
		$headers = "MIME-Version: 1.0\r\n" . "Content-type: text/html; charset=iso-8859-1\r\n" . "From: Speeltuinzoeker <info@speeltuinzoeker.nl>\r\n" . "Reply-To: info@speeltuinzoeker.nl\r\n" . "X-Mailer: PHP/" . phpversion ();
		
		mail($to, $subject, $message, $headers);
		
		// test:
		//echo $message;die;
	}
}