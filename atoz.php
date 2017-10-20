<?php
require_once "cfg/config.php";
require_once "admin/inc/functions.php";

$db = new Db();
$db->connect();

$speeltuin = new Speeltuin($db);

$allSpeeltuinen = $speeltuin->getAllSpeeltuinenAtoZ();

$results = false;
$q = "";
if (isset($_GET["letter"])) {
    $results = true;
    $letter = $_GET["letter"];
    $speeltuinen = $allSpeeltuinen[$letter];
} else if (isset($_GET["q"])) {
    $results = true;
    $q = $_GET["q"];
    $speeltuinen = $speeltuin->search($q);
}

include "tpl/atoz.tpl.php";