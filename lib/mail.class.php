<?php
class Mail {
	public static function sendMail($to, $subject, $message, $cc = null, $replyTo = "info@speeltuinzoeker.nl") {
		$message = "<html><head><title>" . $subject . "</title></head>" . "<body style=\"font-family: Arial;\">" . $message . "</body>";
		
		$headers = "MIME-Version: 1.0\r\n" . 
			"Content-type: text/html; charset=iso-8859-1\r\n" . 
			"From: Speeltuinzoeker <info@speeltuinzoeker.nl>\r\n" . 
			($cc != null ? "Cc: " . $cc . "\r\n" : "") .
			"Reply-To: " . $replyTo . "\r\n" . 
			"X-Mailer: PHP/" . phpversion();
		
		mail($to, $subject, $message, $headers);
		
		// test:
		//echo $message;die;
	}
	
	// Mail templates:
	
	public static function sendReviewAcceptedToAuthor($review) {
		$userEmail = $review->getAuthorEmail();
		if ($userEmail != null) {
			$message = "<p>Beste " . $review->getAuthorName() . ",</p>" .
				"<p>Je beoordeling van speeltuin \"" . $review->getSpeeltuinName() . "\" is goedgekeurd!</p>" .
				"<p>Hij is nu <a href='" . BASE_URL . "speeltuinen/" . $review->getSpeeltuinSeoUrl() . "'>zichtbaar</a> op de site.</p>" .
				"<p>Met vriendelijke groeten,<br>" .
				"Het team van Speeltuinzoeker.nl</p>";
			self::sendMail($userEmail, "Beoordeling van speeltuin " . $review->getSpeeltuinName() . " goedgekeurd", $message);
		}
	}
	
	public static function sendReviewAcceptedToSpeeltuinAuthor($review) {
		$userEmail = $review->getSpeeltuinAuthorEmail();
		if ($userEmail != null) {
			$message = "<p>Beste " . $review->getSpeeltuinAuthorName() . ",</p>" .
					"<p>Je speeltuin \"" . $review->getSpeeltuinName() . "\" is zojuist beoordeeld door " . $review->getAuthorName() . ".</p>" .
					"<p>Deze beoordeling is nu <a href='" . BASE_URL . "speeltuinen/" . $review->getSpeeltuinSeoUrl() . "'>zichtbaar</a> op de site.</p>" .
					"<p>Met vriendelijke groeten,<br>" .
					"Het team van Speeltuinzoeker.nl</p>";
			self::sendMail($userEmail, "Speeltuin " . $review->getSpeeltuinName() . " beoordeeld", $message);
		}
	}
	
	public static function sendReviewRejectedToAuthor($review, $reason) {
		$userEmail = $review->getAuthorEmail();
		if ($userEmail != null) {
			$message = "<p>Beste " . $review->getAuthorName() . ",</p>" .
					"<p>Je beoordeling van speeltuin \"" . $review->getSpeeltuinName() . "\" is helaas afgekeurd.</p>" .
					"<p>De reden hiervan is: " . $reason . "</p>" .
					"<p>De beoordeling staat nog steeds in je <a href='" . BASE_URL . "admin/review.php?user'>overzicht</a>." .
					"Je kunt hem eventueel bewerken en opslaan, zodat we hem opnieuw kunnen beoordelen.</p>" .
					"<p>Met vriendelijke groeten,<br>" .
					"Het team van Speeltuinzoeker.nl</p>";
			self::sendMail($userEmail, "Beoordeling van speeltuin " . $review->getSpeeltuinName() . " afgekeurd", $message);
		}
	}
	
	public static function sendUpdatedReviewToAdmin($review) {
		$message = "<p>Gebruiker " . $review->getAuthorName() . " heeft zijn/haar beoordeling van speeltuin \"" . $review->getSpeeltuinName() . "\" bewerkt.</p>" . "<p><a href='" . BASE_URL . "admin/edit_review.php?id=" . $review->getId() . "'>Controleer deze beoordeling</a></p>";
		self::sendMail(ADMIN_MAIL, "Beoordeling van speeltuin " . $review->getSpeeltuinName() . " bewerkt", $message);
		
	}
	
	public static function sendNewProposal($speeltuin) {
		$message = "<p>Beste Erik,</p><p>Een bezoeker heeft een nieuwe speeltuin \"" . $speeltuin->getName() . "\" voorgesteld.</p>" . "<p><a href='" . BASE_URL . "admin/edit.php?id=" . $speeltuin->getId() . "'>Bewerk deze speeltuin</a></p>";
		Mail::sendMail("tom.erik.roos@gmail.com", "Nieuwe voorgestelde speeltuin " . $speeltuin->getName(), $message);
	}
	
	// TODO rest van de mails ook hier
}