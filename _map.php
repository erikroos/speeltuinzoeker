<?php
require_once "./cfg/config.php";
require_once "./admin/inc/functions.php";

$db = new Db();
$db->connect();

$speeltuinen = [];

$res = $db->query("SELECT * FROM speeltuin WHERE status_id = 1");
while ($row = $res->fetch_assoc()) {
	$speeltuinen[] = $row;
}