<?php
require_once "cfg/config.php";
require_once "admin/inc/functions.php";

$ne = get_request_value("ne", "");
$neParts = explode(",", $ne);
$neLat = $neParts[0];
$neLon = $neParts[1];

$sw = get_request_value("sw", "");
$swParts = explode(",", $sw);
$swLat = $swParts[0];
$swLon = $swParts[1];

$db = new Db();
$speeltuin = new Speeltuin($db);
$speeltuinenInBox = $speeltuin->getAllSpeeltuinenInBoundingBox($neLat, $neLon, $swLat, $swLon);

echo json_encode($speeltuinenInBox);

exit;