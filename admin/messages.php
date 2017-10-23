<?php
require_once "../cfg/config.php";
require_once "./inc/functions.php";

$start = get_request_value("start", 0);
$size = get_request_value("size", 10);

$pageTitle = "Notificaties";

$db = new Db();
$db->connect();

$message = new Message($db);
$totalSize = $message->getTotalNr();
$rows = $message->getMessages($start, $size);

include "tpl/messages.tpl.php";