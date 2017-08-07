<?php
require_once "../cfg/config.php";
require_once "./inc/functions.php";

if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
	
	$db = new Db();
	$db->connect();
	$auth = new Auth($db);
	
	if ($auth->login($_POST["login"], $_POST["password"])) {
		header ( "Location: index.php" );
		exit ();
	}
	
	$feedback = "Ongeldige inloggegevens.";
}

include "tpl/login.tpl.php";