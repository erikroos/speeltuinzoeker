<?php
require_once "./cfg/config.php";
require_once "./admin/inc/functions.php";

$db = new Db();
$db->connect();

$speeltuinen = [];

$res = $db->query ("SELECT * FROM speeltuin WHERE status_id = 1");
while ($row = $res->fetch_assoc()) {
	$speeltuinen[] = $row;
}

$fromSpeeltuin = false;
if (isset($_GET["speeltuin"])) {
	$id = get_request_value("speeltuin", 0);
	if (is_numeric($id) && $id > 0) {
		$fromSpeeltuin = true;
		$speeltuin = new Speeltuin($db, $id);
		$lat = $speeltuin->getLatitude();
		$lon = $speeltuin->getLongitude();
	}
}