<?php
require_once "../cfg/config.php";
require_once "./inc/functions.php";

$db = new Db();
$db->connect();

$speeltuin = new Speeltuin($db);

$nrActive = $speeltuin->getTotalNr(1);
$nrProposed = $speeltuin->getTotalNr(0);
$nrRejected = $speeltuin->getTotalNr(2);

$nrOfUsers = 0; // TODO
$nrOfItems = 0; // TODO

include "tpl/index.tpl.php";