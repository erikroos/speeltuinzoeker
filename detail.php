<?php
require_once "./cfg/config.php";
require_once "./admin/inc/functions.php";

$id = get_request_value("speeltuin", 0);

$db = new Db ();
$db->connect ();

$speeltuin = new Speeltuin($db, $id);

$alleVoorzieningen = $speeltuin->getAllVoorzieningen();

include "tpl/detail.tpl.php";