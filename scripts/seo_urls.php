<?php
die("die before you try");

require_once "../cfg/config.php";
require_once "../admin/inc/functions.php";

$db = new Db();
$db->connect();

$speeltuin = new Speeltuin($db);

$res = $db->query("SELECT id, naam FROM speeltuin");
if ($res !== false) {
	while ($row = $res->fetch_assoc()) {
		$seoUrl = $speeltuin->toSeoUrl($row["naam"]);
		$db->query(sprintf("UPDATE speeltuin SET seo_url = \"%s\" WHERE id = %d", $seoUrl, $row["id"]));
		echo "#" . $row["id"] . " naam = " . $row["naam"] . " seo_url = " . $seoUrl . "<br>";
	}
}

echo "Kloar!";