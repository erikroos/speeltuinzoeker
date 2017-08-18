<?php
require_once "../cfg/config.php";
require_once "./inc/functions.php";

$db = new Db();
$db->connect();

$id = get_request_value("id", 0);
if (!is_numeric($id)) {
	die("Ongeldig ID!");
}

$item = new Item($db, $id);

$del = get_request_value("del", 0);

if ($del == 1) {
	// TODO verwijderen
	header("Location: items.php");
	exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// TODO opslaan/bewerken
	header("Location: items.php");
	exit;
}

if ($id == 0) {
	$name = "";
	$pageTitle = "Nieuwe voorziening";
} else {
	$name = $item->getName();
	$pageTitle = "Voorziening " . $name;
}

include "tpl/item.tpl.php";