<?php
require_once "../cfg/config.php";
require_once "./inc/functions.php";

$name = "";
$email = "";
$password = "";
$showForm = true;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	$feedback = null;
	
	$db = new Db();
	$db->connect();
	$auth = new Auth($db);
	
	$name = sanitizeInput(get_request_value("naam", ""));
	$email = sanitizeInput(get_request_value("email", ""));
	$password = get_request_value("password", "");
	$password2 = get_request_value("password2", "");
	$antispam = get_request_value("antispam", 0);
	
	if ($antispam != 2) {
		$feedback = "Het antwoord op de anti-spamvraag is niet correct.";
	}
	
	if ($feedback == null && $password != $password2) {
		$feedback = "De wachtwoorden komen niet overeen.";
	}
	
	if ($feedback == null && (empty($name) || empty($email) || empty($password) || empty($password2))) {
		$feedback = "Vul alle velden in.";
	}
	
	// TODO validiteit van variabelen, m.n. email, controleren + kwaliteit van password
	
	if ($feedback == null) {
		if (!$auth->userExists($email)) {
			if ($auth->createNewAccount($name, $email, $password)) {
				$feedback = "Aanmelden gelukt! Je ontvangt nu een e-mail met instructies om je aanmelding te activeren.";
				
				$message = "<p>Gebruiker " . $name . " (e-mail: " . $email . ") heeft een account aangemaakt.</p>";
				Mail::sendMail(ADMIN_MAIL, "Gebruiker " . $name . " aangemaakt", $message);
				
				$showForm = false;
			} else {
				$feedback = "Aanmelden mislukt...";
			}
		} else {
			$feedback = "Er is al een gebruiker met dit e-mailadres.";
		}
	}
}

include "tpl/register.tpl.php";