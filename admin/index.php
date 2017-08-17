<?php
require_once "../cfg/config.php";
require_once "./inc/functions.php";

$db = new Db();
$db->connect();

$speeltuin = new Speeltuin($db);
$user = new User($db);

$nrActive = $speeltuin->getTotalNr(1);
$nrProposed = $speeltuin->getTotalNr(0);
$nrRejected = $speeltuin->getTotalNr(2);

$nrOfUsers = $user->getTotalNr(1);
$nrOfItems = 0; // TODO

include "tpl/index.tpl.php";