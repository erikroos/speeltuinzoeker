<?php
session_start();

require_once "./cfg/config.php";
require_once "./admin/inc/functions.php";

$id = get_request_value("speeltuin", 0);

if (!is_numeric($id) || $id <= 0) {
	die("Ongeldig ID!");
}

$db = new Db();
$db->connect();

$speeltuin = new Speeltuin($db, $id);

if (!$speeltuin->isExistingId($id)) {
	die("Niet-bestaande speeltuin!");
}

$photos = $speeltuin->getPhotos();

$alleVoorzieningen = $speeltuin->getAllVoorzieningen();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (isset($_SESSION["user_id"]) && $_SESSION["user_id"] == $_POST["userId"]) {
		
		$poster = new User($db, $_POST["userId"]);
		
		$subject = "Verzoek tot wijziging speeltuin " . $speeltuin->getName();

		$comment = sanitizeInput($_POST["comment"]);

		if (!empty($comment)) {
            $message = "<p>Beste " . $speeltuin->getAuthorName() . ",</p>" .
                "<p>Gebruiker " . $poster->getName() . " heeft een wijzigingsverzoek verstuurd over jouw speeltuin \"" . $speeltuin->getName() . "\":</p>" .
                "<p>" . $comment . "</p>" .
                "<p>Je kunt de speeltuin <a href=\"" . BASE_URL . "admin/edit.php?id=" . $id . "\">bewerken</a> in Mijn Speeltuinzoeker.</p>" .
                "<p>Je kunt contact opnemen met " . $poster->getName() . " door op deze e-mail te antwoorden.</p>" .
                "<p>Met vriendelijke groeten,<br>" .
                "Het team van Speeltuinzoeker.nl</p>";

            Mail::sendMail($speeltuin->getAuthorEmail(), $subject, $message, "info@speeltuinzoeker.nl," . $poster->getEmail(), $poster->getEmail());

            $sent = true;
        } else {
            $sent = false;
        }
		
	} else {
		$sent = false;
	}
}

include "tpl/detail.tpl.php";