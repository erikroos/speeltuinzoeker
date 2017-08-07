<?php
require_once "../cfg/config.php";
require_once "./inc/functions.php";

$id = get_request_value ( "id", 0 );

$db = new Db ();
$db->connect ();

$speeltuin = new Speeltuin ( $db, $id );

// Stap 1: check of admin of auteur van deze speeltuin, anders heb je hier niets te zoeken
$isUser = false;
$isAdmin = false;
if ($_SESSION ["admin"] == 1) {
	$isAdmin = true;
} else {
	if ($id > 0) {
		if ($speeltuin->getAuthor () != $_SESSION ["user_id"]) {
			$_SESSION ["feedback"] = "Bekijken van deze speeltuin niet toegestaan.";
			header ( "Location: index.php" );
			exit ();
		}
	}
	$isUser = true;
}

if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
	
	if ($_POST ["Submit"] == "Keur goed") {
		
		$speeltuin->activate ();
		
		// TODO mail sturen aan author
		
		$_SESSION ["feedback"] = "Speeltuin goedgekeurd.";
		header ( "Location: view.php?status=1" );
		exit ();
	} else if ($_POST ["Submit"] == "Keur af") {
		
		$speeltuin->deactivate ();
		
		// TODO mail sturen aan author met reden $_POST["afkeur_reden"]
		
		$_SESSION ["feedback"] = "Speeltuin afgekeurd.";
		header ( "Location: view.php?status=2" );
		exit ();
	} else {
		
		$name = get_request_value ( "naam", "" );
		$omschrijving = get_request_value ( "omschrijving", "" );
		$locatieOmschrijving = get_request_value ( "locatie_omschrijving", "" );
		$lat = get_request_value ( "lat", 0.0 );
		$lon = get_request_value ( "lon", 0.0 );
		
		$speeltuin->insertOrUpdate ( $name, $omschrijving, $locatieOmschrijving, $lat, $lon, $id );
		
		if ($id == 0) {
			$id = $speeltuin->getId();
			$_SESSION ["feedback"] = "Speeltuin toegevoegd!<br>
					De speeltuin staat op inactief en is nog niet zichtbaar tot deze gecontroleerd is. 
					We streven ernaar dit binnen 24 uur te doen.";
			$message = "<p>Gebruiker " . $_SESSION["user_name"] . " heeft een nieuwe speeltuin toegevoegd.</p>" . "<p><a href='" . BASE_URL . "admin/edit.php?id=" . $id . "'>Controleer deze speeltuin</a></p>";
			Mail::sendMail(ADMIN_MAIL, "Nieuwe speeltuin", $message);
		} else {
			$_SESSION ["feedback"] = "Speeltuin aanpassen gelukt!<br>
					De speeltuin staat nu tijdelijk op inactief en is niet zichtbaar tot deze gecontroleerd is. 
					We streven ernaar dit binnen 24 uur te doen.";
			$message = "<p>Gebruiker " . $_SESSION["user_name"] . " heeft de speeltuin \"" . $name . "\" bewerkt.</p>" . "<p><a href='" . BASE_URL . "admin/edit.php?id=" . $id . "'>Controleer deze speeltuin</a></p>";
			Mail::sendMail (ADMIN_MAIL, "Bewerkte speeltuin", $message);
		}
		
		$speeltuin->setVoorzieningen($_POST);
		
		header("Location: view.php?user");
		exit();
	}
} else {
	
	$name = "";
	$omschrijving = "";
	$locatieOmschrijving = "";
	$lat = 0.0;
	$lon = 0.0;
	$status_id = 0;
	
	$allVoorzieningen = $speeltuin->getAllVoorzieningen ();
	$selectedVoorzieningen = array ();
	$photos = array ();
	
	if ($id > 0) { // bestaand
		list ( $name, $omschrijving, $locatieOmschrijving, $lat, $lon, $status_id ) = $speeltuin->getFields ();
		
		$selectedVoorzieningen = $speeltuin->getVoorzieningen ();
		
		$photos = $speeltuin->getPhotos ();
	}
}

if ($id == 0) {
	$pageTitle = "Voeg een speeltuin toe";
} else {
	if ($isUser) {
		$pageTitle = "Bewerk speeltuin " . $name;
	} else { // admin
		if ($status_id == 0) {
			$pageTitle = "Keur speeltuin " . $name . " goed of af";
		} else if ($status_id == 1 || $status_id == 2) {
			$pageTitle = "Bekijk speeltuin " . $name;
		}
	}
}

include "tpl/edit.tpl.php";