<?php
require_once "../cfg/config.php";
require_once "./inc/functions.php";

$db = new Db();
$db->connect();

$speeltuin = new Speeltuin($db);
$user = new User($db);
$item = new Item($db);

$nrActive = $speeltuin->getTotalNr(1);
$nrProposed = $speeltuin->getTotalNr(0);
$nrRejected = $speeltuin->getTotalNr(2);

$nrOfUsers = $user->getTotalNr(1);
$nrOfItems = $item->getTotalNr();

include "tpl/index.tpl.php";