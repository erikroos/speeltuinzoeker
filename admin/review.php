<?php
require_once "../cfg/config.php";
require_once "./inc/functions.php";

$start = get_request_value("start", 0);
$size = get_request_value("size", 10);
$q = get_request_value("q", "");

$db = new Db();
$db->connect();

$isUser = false;
$isAdmin = false;
if (isset($_GET["user"])) {
	$isUser = true;
	$pageTitle = "Mijn beoordelingen";
} else if (isset($_GET["status"]) && is_numeric($_GET["status"]) && $_SESSION["admin"] == 1) {
	$isAdmin = true;
	$status = $_GET["status"];
	switch ($status) {
		case 0:
			$pageTitle = "Voorgestelde beoordelingen";
			break;
		case 1:
			$pageTitle = "Actieve beoordelingen";
			break;
		case 2:
			$pageTitle = "Afgewezen beoordelingen";
			break;
	}
} else {
	$_SESSION["feedback"] = "Bekijken en beheren niet toegestaan.";
	header("Location: index.php");
	exit();
}

if ($isAdmin && isset($_GET["del"])) {
	if (is_numeric($_GET["del"]) && $_GET["del"] > 0) {
		$review = new Review($db, $_GET["del"]);
		$review->delete();
	}
	header("Location: review.php?status=" . $status . "&start=" . $start);
	exit();
}

$totalSize = 0;
if ($isUser) {
	$whereClause = "WHERE user_id = " . $_SESSION["user_id"];
} else {
	$whereClause = "WHERE status = " . $status;
}
$limitClause = "LIMIT " . $start . "," . $size;

if (!empty($q)) {
	$whereClause .= " AND (review.comment LIKE \"%" . $q . "%\" OR speeltuinNaam LIKE \"%" . $q . "%\")";
	$limitClause = "";
}

$res = $db->query(sprintf("SELECT COUNT(id) AS totalSize FROM review %s", $whereClause));
if ($res !== false) {
	if ($row = $res->fetch_assoc()) {
		$totalSize = $row["totalSize"];
	}
}

$res = $db->query(sprintf("SELECT review.*,	user.naam AS userNaam, speeltuin.naam AS speeltuinNaam
			FROM review
			LEFT JOIN user ON review.user_id = user.id
			LEFT JOIN speeltuin ON review.speeltuin_id = speeltuin.id
			%s
			GROUP BY review.id
			%s", $whereClause, $limitClause));
$rows = array ();
if ($res !== false) {
	while ($row = $res->fetch_assoc()) {
		// status
		switch ($row["status"]) {
			case 0:
				$row["status"] = "Voorgesteld";
				break;
			case 1:
				$row["status"] = "Actief";
				break;
			case 2:
				$row["status"] = "Afgewezen";
				break;
			default:
				$row["status"] = "Onbekend";
		}
		$rows[] = $row;
	}
}
?>

<?php
include "tpl/review.tpl.php";