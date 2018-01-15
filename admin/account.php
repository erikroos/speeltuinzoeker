<?php
require_once "../cfg/config.php";
require_once "./inc/functions.php";

$db = new Db();
$db->connect();

$auth = new Auth($db);
$user = $auth->getLoggedInUser();
$name = $user->getName();
$email = $user->getEmail();
$nrOfLogins = $user->getNrOfLogins();
$lastLogin = $user->getLastLogin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if ($_POST["Submit"] == "Wijzigen") {
		$newName = get_request_value("naam", "");
		if (!empty($newName) && $newName != $name) {
			$user->setName($newName);
			$feedback = "Naam gewijzigd.";
			$name = $newName;
		}
		
		// TODO kwaliteit van password controleren
		$newPassword = get_request_value("password", "");
		if (!empty($newPassword)) {
			$password2 = get_request_value("password2", "");
			if (!empty($newPassword) && $newPassword == $password2) {
				$user->setPassword($auth, $newPassword);
				$user->setPasswordGenerated(0);
				$_SESSION["password_generated"] = 0;
				if (isset($feedback)) {
					$feedback .= "<br>Wachtwoord gewijzigd.";
				} else {
					$feedback = "Wachtwoord gewijzigd.";
				}
			} else {
				if (empty($newPassword)) {
					if (isset($feedback)) {
						$feedback .= "<br>Wachtwoord <strong>niet</strong> gewijzigd: mag niet leeg zijn.";
					} else {
						$feedback = "Wachtwoord <strong>niet</strong> gewijzigd: mag niet leeg zijn.";
					}
				} else {
					if (isset($feedback)) {
						$feedback .= "<br>Wachtwoord <strong>niet</strong> gewijzigd: wachtwoorden komen niet overeen.";
					} else {
						$feedback = "Wachtwoord <strong>niet</strong> gewijzigd: wachtwoorden komen niet overeen.";
					}
				}
			}
		}
	} else if ($_POST["Submit"] == "Account opheffen") {
		$auth->logout();
		$user->delete();
		Mail::sendAccountDeletedToAdmin($name, $email);
		header("Location: deleted.php");
		exit;
	}
}

include "tpl/account.tpl.php";