<?php
require_once "../cfg/config.php";
require_once "./inc/functions.php";

$id = get_request_value("id", 0);
$photoNr = get_request_value("photoNr", "");

$db = new Db();
$db->connect();

$speeltuin = new Speeltuin($db, $id);

// Stap 1: check of auteur van deze speeltuin, anders heb je hier niets te zoeken
if ($speeltuin->getAuthor() != $_SESSION["user_id"]) {
	exit;
}

// Delete photo
if (!empty($photoNr)) {
	$speeltuin->removePhoto("speeltuin" . $id . "_" . $photoNr . ".png");
}

// Re-render bar
$html = "";
$existingPhotos = $speeltuin->getPhotos();
if (sizeof($existingPhotos) > 0) {
	$html .= "<ul>";
	$photoNr = 1;
	foreach ($existingPhotos as $photo) {
		
		$extension = strtolower(end(explode('.', $photo)));
		list($width, $height, $type, $attr) = getimagesize($photo);
		$size = filesize(str_replace(MEDIA_URL, MEDIA_PATH, $photo));
		
		$html .= "<li>";
		$html .= "<img src='" . $photo . "' alt='Foto van deze speeltuin' title='Foto van deze speeltuin' />";
		$html .= "<span>" . strtoupper($extension) . ", " . $width . "x" . $height . " px, " . humanFileSize($size) . "</span>";
		$html .= "<input id='del_photo_" . $photoNr . "' type='button' value='Verwijder' class='btn btn-default del_photo_btn' onclick='deletePhoto(this.id); return false;' />";
		$html .= "</li>";
		$photoNr++;
	}
	$html .= "</ul>";
} else {
	$html .= "<p>Er zijn geen foto's.</p>";
}

echo $html;
exit;