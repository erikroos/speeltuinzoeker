<?php
require_once "../cfg/config.php";
require_once "./inc/functions.php";

$msgId = get_request_value("id", 0);

if ($msgId > 0 && isset($_SESSION["user_id"])) {
	$db = new Db();
	$db->connect();
	$msg = new Message($db, $msgId);
	$msg->markAsRead($_SESSION["user_id"]);
}