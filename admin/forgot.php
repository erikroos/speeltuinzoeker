<?php
require_once "../cfg/config.php";
require_once "./inc/functions.php";

$showForm = true;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	$feedback = null;
	
	$db = new Db();
	$db->connect();
	$auth = new Auth($db);
	$user = new User($db);
	
	$email = get_request_value("email", "");
	
	if ($user->getByEmail($email)) {
		
		$newPass = $auth->randomPassword();
		
		$user->setPassword($auth, $newPass);
		$user->setPasswordGenerated(1);
		
		Mail::sendNewPassword($user->getName(), $newPass, $email);
	}
	
	$feedback = "Als je het juiste e-mailadres hebt opgegeven, dan ontvang je nu een e-mail met een nieuw (tijdelijk) wachtwoord.";
	$showForm = false;
}

include "tpl/forgot.tpl.php";