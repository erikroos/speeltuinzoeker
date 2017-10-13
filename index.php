<?php
session_start();

require_once "./cfg/config.php";
require_once "./admin/inc/functions.php";

$db = new Db();
$db->connect();

$speeltuin = new Speeltuin($db);

$speeltuinen = $speeltuin->getAllSpeeltuinen();

$fromSpeeltuin = null;
if (isset($_GET["speeltuin"])) {
    $id = get_request_value("speeltuin", 0);
    if (is_numeric($id) && $id > 0) {
        $fromSpeeltuin = new Speeltuin($db, $id);
    }
}

$totalNr = $speeltuin->getTotalNr();
$latestSpeeltuin = $speeltuin->getLatestEntry();

$user = new User($db);
$totalNrOfUsers = $user->getTotalNr(1);

$defaultLocationString = "Je locatie, bijvoorbeeld: Fazantweg, Paterswolde";

include "tpl/index.tpl.php";