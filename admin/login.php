<?php
require_once "../cfg/config.php";
require_once "./inc/functions.php";

if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
	
	$db = new Db();
	$db->connect();
	$auth = new Auth($db);
	
	if ($auth->login($_POST["login"], $_POST["password"])) {
		if ($_SESSION["admin"] == 1) {
			header("Location: index.php");
		} else {
			header("Location: view.php?user");
		}
		exit();
	}
	
	$feedback = "Ongeldige inloggegevens.";
}

include "tpl/login.tpl.php";