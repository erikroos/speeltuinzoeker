<?php
require_once "cfg/config.php";
require_once "admin/inc/functions.php";

$db = new Db();
$db->connect();

$speeltuin = new Speeltuin($db);

$allSpeeltuinen = $speeltuin->getAllSpeeltuinenAtoZ();

include "tpl/atoz.tpl.php";