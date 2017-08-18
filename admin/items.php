<?php
require_once "../cfg/config.php";
require_once "./inc/functions.php";

$pageTitle = "Voorzieningen";

$db = new Db();
$db->connect();

$item = new Item($db);

$rows = $item->getAllItems();

include "tpl/items.tpl.php";