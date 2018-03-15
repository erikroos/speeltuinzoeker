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
	
	public static function sendNewProposal($speeltuin, $defaultUserEmail) {
		$message = "<p>Beste Erik,</p><p>Een bezoeker heeft een nieuwe speeltuin \"" . $speeltuin->getName() . "\" voorgesteld.</p>" . "<p><a href='" . BASE_URL . "admin/edit.php?id=" . $speeltuin->getId() . "'>Bewerk deze speeltuin</a></p>";
		self::sendMail($defaultUserEmail, "Nieuwe voorgestelde speeltuin " . $speeltuin->getName(), $message);
	}
	
	public static function sendAccountDeactivated($userName, $reason, $email) {
		$message = "<p>Beste " . $userName . ",</p>" .
				"<p>Je account bij Speeltuinzoeker.nl is op non-actief gezet, met als reden: " . $reason . "</p>" .
				"<p>Met vriendelijke groeten,<br>" .
				"Het team van Speeltuinzoeker.nl</p>";
		self::sendMail($email, "Je account bij Speeltuinzoeker.nl", $message);
	}
	
	public static function sendSpeeltuinAcceptedToAuthor($speeltuin) {
		$userEmail = $speeltuin->getAuthorEmail();
		if ($userEmail != null) {
			$message = "<p>Beste " . $speeltuin->getAuthorName() . ",</p>" .
				"<p>Je speeltuin \"" . $speeltuin->getName() . "\" is goedgekeurd!</p>" .
				"<p>Hij is nu <a href='" . BASE_URL . "?speeltuin=" . $speeltuin->getId() . "'>zichtbaar</a> op de site.</p>" .
				"<p>Met vriendelijke groeten,<br>" .
				"Het team van Speeltuinzoeker.nl</p>";
			self::sendMail($userEmail, "Speeltuin " . $speeltuin->getName() . " goedgekeurd", $message);
		}
	}
	
	public static function sendSpeeltuinRejectedToAuthor($speeltuin) {
		$userEmail = $speeltuin->getAuthorEmail();
		if ($userEmail != null) {
			$message = "<p>Beste " . $speeltuin->getAuthorName() . ",</p>" .
					"<p>Je speeltuin \"" . $speeltuin->getName() . "\" is helaas afgekeurd.</p>" .
					"<p>De reden hiervan is: " . $_POST["afkeur_reden"] . "</p>" .
					"<p>De speeltuin staat nog steeds in je <a href='" . BASE_URL . "admin/view.php?user'>overzicht</a>. " .
					"Je kunt hem eventueel bewerken en opslaan, zodat we hem opnieuw kunnen beoordelen.</p>" .
					"<p>Met vriendelijke groeten,<br>" .
					"Het team van Speeltuinzoeker.nl</p>";
			self::sendMail($userEmail, "Speeltuin " . $speeltuin->getName() . " afgekeurd", $message);
		}
	}
	
	public static function sendSpeeltuinAddedToAdmin($userName, $id, $name) {
		$message = "<p>Gebruiker " . $userName . " heeft een nieuwe speeltuin toegevoegd.</p>" . "<p><a href='" . BASE_URL . "admin/edit.php?id=" . $id . "'>Controleer deze speeltuin</a></p>";
		self::sendMail(ADMIN_MAIL, "Nieuwe speeltuin " . $name, $message);
	}
	
	public static function sendSpeeltuinUpdatedToAdmin($userName, $id, $name) {
		$message = "<p>Gebruiker " . $userName . " heeft de speeltuin \"" . $name . "\" bewerkt.</p>" . "<p><a href='" . BASE_URL . "admin/edit.php?id=" . $id . "'>Controleer deze speeltuin</a></p>";
		self::sendMail(ADMIN_MAIL, "Speeltuin " . $name . " bewerkt", $message);
	}
	
	public static function sendNewPassword($userName, $newPass, $email) {
		$message = "<p>Beste " . $userName . ",</p>" .
				"<p>Je hebt een nieuw wachtwoord aangevraagd voor Speeltuinzoeker.nl.</p>" .
				"<p>Je kunt nu inloggen met het wachtwoord: " . $newPass . "</p>" .
				"<p>Met vriendelijke groeten,<br>" .
				"Het team van Speeltuinzoeker.nl</p>";
		self::sendMail($email, "Wachtwoord Speeltuinzoeker.nl", $message);
	}
	
	public static function sendPhotosUpdatedToAdmin($userName, $name, $id) {
		$message = "<p>Gebruiker " . $userName . " heeft de foto's van de speeltuin \"" . $name . "\" aangepast.</p>" . "<p><a href='" . BASE_URL . "admin/edit.php?id=" . $id . "'>Controleer deze speeltuin</a></p>";
		self::sendMail(ADMIN_MAIL, "Foto's speeltuin " . $name . " bewerkt", $message);
	}
	
	public static function sendUserAddedToAdmin($name, $email) {
		$message = "<p>Gebruiker " . $name . " (e-mail: " . $email . ") heeft een account aangemaakt.</p>";
		self::sendMail(ADMIN_MAIL, "Gebruiker " . $name . " aangemaakt", $message);
	}
	
	public static function sendActivationInstructions($name, $activationCode, $email) {
		$message = "<p>Beste " . $name . ",</p>" .
				"<p>Je hebt je aangemeld voor een account bij Speeltuinzoeker.nl. Welkom!<br>" .
				"Je hoeft alleen nog even op " .
				"<a href='" . BASE_URL . "admin/activate.php?code=" . $activationCode . "'>deze link</a>" .
				" te klikken om je account te activeren." .
				"Daarna kun je direct inloggen en beginnen met het invoeren en beoordelen van speeltuinen!</p>" .
				"<p>Met vriendelijke groeten,<br>" .
				"Het team van Speeltuinzoeker.nl</p>" .
				"<p>PS: werkt de link niet? Voer dan dit webadres in in de adresbalk van je browser:<br>" . BASE_URL . "admin/activate.php?code=" . $activationCode . "</p>";
		self::sendMail($email, "Activeer je aanmelding bij Speeltuinzoeker.nl", $message);
	}
	
	public static function sendUserActivatedToAdmin($naam, $email) {
		$message = "<p>Gebruiker " . $naam . " (e-mail: " . $email . ") heeft zijn/haar account geactiveerd.</p>";
		self::sendMail(ADMIN_MAIL, "Gebruiker " . $naam . " geactiveerd", $message);
	}
	
	public static function sendUpdateRequestToAuthor($speeltuin, $poster, $id, $comment) {
		$message = "<p>Beste " . $speeltuin->getAuthorName() . ",</p>" .
				"<p>Gebruiker " . $poster->getName() . " heeft een wijzigingsverzoek verstuurd over jouw speeltuin \"" . $speeltuin->getName() . "\":</p>" .
				"<p>" . $comment . "</p>" .
				"<p>Je kunt de speeltuin <a href=\"" . BASE_URL . "admin/edit.php?id=" . $id . "\">bewerken</a> in Mijn Speeltuinzoeker.</p>" .
				"<p>Je kunt contact opnemen met " . $poster->getName() . " door op deze e-mail te antwoorden.</p>" .
				"<p>Met vriendelijke groeten,<br>" .
				"Het team van Speeltuinzoeker.nl</p>";
		self::sendMail($speeltuin->getAuthorEmail(),
			"Verzoek tot wijziging speeltuin " . $speeltuin->getName(),
			$message, "info@speeltuinzoeker.nl," . $poster->getEmail(), $poster->getEmail());
		
	}
	
	public static function sendAccountDeletedToAdmin($naam, $email) {
		$message = "<p>Gebruiker " . $naam . " (e-mail: " . $email . ") heeft zijn/haar account opgeheven.</p>" . 
				"<p>Speeltuinen en beoordelingen zijn overgezet naar de standaardgebruiker tom.erik.roos@gmail.com.</p>";
		self::sendMail(ADMIN_MAIL, "Account " . $naam . " opgeheven", $message);
	}
}