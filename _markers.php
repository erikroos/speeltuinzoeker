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

$type = get_request_value("type", array());
$agecat = get_request_value("agecat", array());
$access = get_request_value("access", array());
$voorziening = get_request_value("voorziening", array());

$db = new Db();
$speeltuin = new Speeltuin($db);
$speeltuinenInBox = $speeltuin->getAllSpeeltuinenInBoundingBox($neLat, $neLon, $swLat, $swLon, 
		$type, $agecat, $access, $voorziening);

echo json_encode($speeltuinenInBox);

exit;