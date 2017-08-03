<?php
require_once "../cfg/config.php";
require_once "./inc/functions.php";

$name = "";
$email = "";
$password = "";
$showForm = true;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$db = new Db();
	$db->connect();
	$auth = new Auth($db);
	
	$name = get_request_value("naam", "");
	$email = get_request_value("email", "");
	$password = get_request_value("password", "");
	
	if (!empty($name) && !empty($email) && !empty($password)) {
		
		// TODO validiteit van variabelen controleren
		
		if (!$auth->userExists($email)) {
			
			$auth->createNewAccount($name, $email, $password, $salt);
		
			$feedback = "Aanmelden gelukt! Je ontvangt nu een e-mail met instructies om je aanmelding te activeren.";
			$showForm = false;
		
		} else {
			$feedback = "Er is al een gebruiker met dit e-mailadres.";
		}
		
	} else {
		$feedback = "Vul alle velden in.";
	}
	
}
	
include "tpl/register.tpl.php";