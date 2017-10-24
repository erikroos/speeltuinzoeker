<?php
require_once "../cfg/config.php";
require_once "./inc/functions.php";

$db = new Db();
$db->connect();

$id = get_request_value("id", 0);
if (!is_numeric($id)) {
	die("Ongeldig ID!");
}

$message = new Message($db, $id);

$del = get_request_value("del", 0);

if ($del == 1) {
	
	$message->delete();
	
	header("Location: messages.php");
	exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
	$body = get_request_value("body", "");
	$message->insertOrUpdate($body);
	
	header("Location: messages.php");
	exit;
}

if ($id == 0) {
	$body = "";
	$pageTitle = "Nieuwe notificatie";
} else {
	$body = $message->getBody();
	$pageTitle = "Bestaande notificatie";
}

include "tpl/message.tpl.php";