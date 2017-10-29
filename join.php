<?php
require_once "cfg/config.php";
require_once "admin/inc/functions.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
	$db = new Db();
	$db->connect();
	
	$speeltuin = new Speeltuin($db, 0);
	
	$name = sanitizeInput(get_request_value("naam", ""));
	$link = "";
	$openingstijden = "";
	$vergoeding = "";
	$omschrijving = sanitizeInput(get_request_value("omschrijving", ""));
	$locatieOmschrijving = sanitizeInput(get_request_value("locatie_omschrijving", ""));
	$lat = get_request_value("lat", 0.0);
	$lon = get_request_value("lon", 0.0);
	$public = 1;
	$type = 1;
	$agecat1 = false;
	$agecat2 = true;
	$agecat3 = true;
	
	$speeltuin->insertOrUpdate($name, $link, $omschrijving, $locatieOmschrijving, $lat, $lon, $public, $type,
			$agecat1, $agecat2, $agecat3, $openingstijden, $vergoeding, 2);
	
	// Photo
	$uploadErrors = [];
	if (isset($_FILES["photo"])) {
		$allowedExtensions = array (
				"jpeg",
				"jpg",
				"png"
		);
		
		$fileName = $_FILES["photo"]["name"];
				
		if ($_FILES["photo"]["error"] == 0) {
	
			$detailError = null;
			$extension = strtolower(end(explode('.', $fileName)));
			if (!in_array($extension, $allowedExtensions)) {
				$detailError = "Fout bij uploaden " . $fileName . ": alleen JP(E)G of PNG toegestaan";
			}
	
			if ($detailError == null) {
				Image::resizeAndConvertToPng($_FILES['photo']['tmp_name']);
				$photoName = "speeltuin" . $speeltuin->getId() . "_1.png";
				move_uploaded_file($_FILES["photo"]["tmp_name"], MEDIA_PATH . $photoName);
				$speeltuin->addPhoto($photoName);
			} else {
				$uploadErrors[] = $detailError;
			}
		} else {
			$uploadErrors[] = "Fout bij uploaden bestand " . $fileName . " (code: " . $_FILES["photo"]["error"] . ")";
		}
	}
	
	// Mail aan gebruiker Erik Roos (ID 2)
	Mail::sendNewProposal($speeltuin);
	
	$feedback = "Bedankt voor je aanmelding! " . 
		"Deze wordt gecontroleerd, waar nodig aangevuld, " .
		"en als hij voldoet aan onze richtlijnen, geplaatst op de site. " .
		"Houd de site de komende tijd dus in de gaten!";
	if (sizeof($uploadErrors) > 0) {
		$feedback .= "<br>Er is nog wel iets misgegaan met de foto:<br>" .  implode("<br>", $uploadErrors);
	}
}

include "tpl/join.tpl.php";