<?php
// included via .htaccess auto prepend

session_start();

$pagesWithoutAuth = [
	"/speeltuinzoeker/admin/login.php",
	"/speeltuinzoeker/admin/logout.php",
	"/speeltuinzoeker/admin/register.php",
	"/speeltuinzoeker/admin/activate.php"
];

if (!in_array($_SERVER["SCRIPT_NAME"], $pagesWithoutAuth) && !isset($_SESSION["user_id"])) {
	header("Location: login.php");
	exit;
}