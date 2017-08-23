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
	$pageTitle = "Mijn speeltuinen";
} else if (isset($_GET["status"]) && is_numeric($_GET["status"]) && $_SESSION["admin"] == 1) {
	$isAdmin = true;
	$status = $_GET["status"];
	switch ($status) {
		case 0:
			$pageTitle = "Voorgestelde speeltuinen";
			break;
		case 1:
			$pageTitle = "Actieve speeltuinen";
			break;
		case 2:
			$pageTitle = "Afgewezen speeltuinen";
			break;
	}
} else {
	$_SESSION["feedback"] = "Bekijken en beheren niet toegestaan.";
	header("Location: index.php");
	exit();
}

$totalSize = 0;
if ($isUser) {
	$whereClause = "WHERE author_id = " . $_SESSION["user_id"];
} else {
	$whereClause = "WHERE status_id = " . $status;
}
$limitClause = "LIMIT " . $start . "," . $size;

if (!empty($q)) {
	$whereClause .= " AND (speeltuin.naam LIKE \"%" . $q . "%\" OR speeltuin.omschrijving LIKE \"%" . $q . "%\" OR speeltuin.locatie_omschrijving LIKE \"%" . $q . "%\")";
	$limitClause = "";
}

$res = $db->query(sprintf("SELECT COUNT(id) AS totalSize FROM speeltuin %s", $whereClause));
if ($res !== false) {
	if ($row = $res->fetch_assoc()) {
		$totalSize = $row["totalSize"];
	}
}

$res = $db->query(sprintf("SELECT speeltuin.id, speeltuin.naam, locatie_omschrijving, omschrijving,
			user.naam AS userNaam, status_id
			FROM speeltuin
			LEFT JOIN user ON speeltuin.author_id = user.id
			%s
			GROUP BY speeltuin.id
			%s", $whereClause, $limitClause));
$rows = array ();
if ($res !== false) {
	while ($row = $res->fetch_assoc()) {
		// aantal voorzieningen
		$row["aantalVoorzieningen"] = 0;
		$res2 = $db->query(sprintf("SELECT COUNT(*) AS aantalVoorzieningen FROM speeltuin_voorziening WHERE speeltuin_id = %d", $row["id"]));
		if ($res2 !== false) {
			if ($row2 = $res2->fetch_assoc()) {
				$row["aantalVoorzieningen"] = $row2["aantalVoorzieningen"];
			}
		}
		// aantal foto's
		$row ["aantalBestanden"] = 0;
		$res2 = $db->query(sprintf("SELECT COUNT(*) AS aantalBestanden FROM speeltuin_bestand WHERE speeltuin_id = %d", $row["id"]));
		if ($res2 !== false) {
			if ($row2 = $res2->fetch_assoc()) {
				$row ["aantalBestanden"] = $row2["aantalBestanden"];
			}
		}
		// status
		switch ($row["status_id"]) {
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
include "tpl/view.tpl.php";