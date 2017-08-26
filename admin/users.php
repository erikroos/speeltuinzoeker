<?php
require_once "../cfg/config.php";
require_once "./inc/functions.php";

$start = get_request_value("start", 0);
$size = get_request_value("size", 10);
$active = get_request_value("active", 1);
$q = get_request_value("q", "");

if ($active == 0) {
	$pageTitle = "Inactieve gebruikers";
} else {
	$pageTitle = "Actieve gebruikers";
}

$db = new Db();
$db->connect();

$user = new User($db);

$rows = $user->getAllUsers($active, $start, $size, $q);
$totalSize = $user->getTotalNr($active, $q);

include "tpl/users.tpl.php";