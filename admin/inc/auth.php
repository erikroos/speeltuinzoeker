<?php
// included via .htaccess auto prepend
session_start();

$pagesWithoutAuth = [ 
		"login.php",
		"logout.php",
		"register.php",
		"activate.php",
		"forgot.php",
		"deleted.php"
];

$uriParts = explode("/", $_SERVER["SCRIPT_NAME"]);

if (!in_array(end($uriParts), $pagesWithoutAuth ) && !isset($_SESSION ["user_id"])) {
	header("Location: login.php");
	exit();
}