<?php
require_once "../cfg/config.php";
require_once "./inc/functions.php";

$id = get_request_value("id", 0);
$start = get_request_value("start", 0);

$db = new Db();
$db->connect();

$speeltuin = new Speeltuin($db, $id);

// Stap 1: check of admin of auteur van deze speeltuin, anders heb je hier niets te zoeken
$isUser = false;
$isAdmin = false;
if ($_SESSION["admin"] == 1) {
	$isAdmin = true;
} else {
	if ($id > 0) {
		if ($speeltuin->getAuthor() != $_SESSION["user_id"]) {
			$_SESSION["feedback"] = "Bekijken van deze speeltuin niet toegestaan.";
			header("Location: index.php");
			exit();
		}
	}
	$isUser = true;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	if ($_POST["Submit"] == "Keur goed") {
		
		$speeltuin->activate();
		
		$userEmail = $speeltuin->getAuthorEmail();
		if ($userEmail != null) {
			$message = "<p>Beste " . $speeltuin->getAuthorName() . ",</p>" . 
				"<p>Je speeltuin \"" . $speeltuin->getName() . "\" is goedgekeurd!</p>" . 
				"<p>Hij is nu <a href='" . BASE_URL . "index.php?speeltuin=" . $speeltuin->getId() . "'>zichtbaar</a> op de site.</p>" . 
				"<p>Met vriendelijke groeten,<br>" . 
				"Het team van Speeltuinzoeker.nl</p>";
			Mail::sendMail($userEmail, "Speeltuin " . $speeltuin->getName() . " geactiveerd", $message);
		}
		
		$_SESSION["feedback"] = "Speeltuin goedgekeurd.";
		header("Location: view.php?status=1&start=" . $start);
		exit();
	} else if ($_POST["Submit"] == "Keur af") {
		
		$speeltuin->deactivate();
		
		$userEmail = $speeltuin->getAuthorEmail();
		if ($userEmail != null) {
			$message = "<p>Beste " . $speeltuin->getAuthorName() . ",</p>" .
					"<p>Je speeltuin \"" . $speeltuin->getName() . "\" is helaas afgekeurd.</p>" .
					"<p>De reden hiervan is: " . $_POST["afkeur_reden"] . "</p>" .
					"<p>Met vriendelijke groeten,<br>" .
					"Het team van Speeltuinzoeker.nl</p>";
			Mail::sendMail($userEmail, "Speeltuin " . $speeltuin->getName() . " geactiveerd", $message);
		}
		
		$_SESSION["feedback"] = "Speeltuin afgekeurd.";
		header("Location: view.php?status=2&start=" . $start);
		exit();
	} else {
		
		$name = get_request_value("naam", "");
		$omschrijving = get_request_value("omschrijving", "");
		$locatieOmschrijving = get_request_value("locatie_omschrijving", "");
		$lat = get_request_value("lat", 0.0);
		$lon = get_request_value("lon", 0.0);
		$public = get_request_value("public", 1);
		
		$speeltuin->insertOrUpdate($name, $omschrijving, $locatieOmschrijving, $lat, $lon, $public);
		
		if ($id == 0) {
			$id = $speeltuin->getId();
			$_SESSION["feedback"] = "Speeltuin toegevoegd!<br>
					De speeltuin staat op inactief en is nog niet zichtbaar tot deze gecontroleerd is. 
					We streven ernaar dit binnen 24 uur te doen.";
			$message = "<p>Gebruiker " . $_SESSION["user_name"] . " heeft een nieuwe speeltuin toegevoegd.</p>" . "<p><a href='" . BASE_URL . "admin/edit.php?id=" . $id . "'>Controleer deze speeltuin</a></p>";
			Mail::sendMail(ADMIN_MAIL, "Nieuwe speeltuin " . $name, $message);
		} else {
			$_SESSION["feedback"] = "Speeltuin aanpassen gelukt!<br>
					De speeltuin staat nu tijdelijk op inactief en is niet zichtbaar tot deze gecontroleerd is. 
					We streven ernaar dit binnen 24 uur te doen.";
			$message = "<p>Gebruiker " . $_SESSION["user_name"] . " heeft de speeltuin \"" . $name . "\" bewerkt.</p>" . "<p><a href='" . BASE_URL . "admin/edit.php?id=" . $id . "'>Controleer deze speeltuin</a></p>";
			Mail::sendMail(ADMIN_MAIL, "Speeltuin " . $name . " bewerkt", $message);
		}
		
		$speeltuin->setVoorzieningen($_POST);
		
		header("Location: view.php?user&start=" . $start);
		exit();
	}
} else {
	
	$name = "";
	$omschrijving = "";
	$locatieOmschrijving = "";
	$lat = 0.0;
	$lon = 0.0;
	$status_id = 0;
	$public = 1;
	
	$allVoorzieningenPop = $speeltuin->getAllVoorzieningen(1);
	$allVoorzieningenNonPop = $speeltuin->getAllVoorzieningen(0);
	$selectedVoorzieningen = [];
	$photos = [];
	
	if ($id > 0) { // bestaand
		list($name, $omschrijving, $locatieOmschrijving, $lat, $lon, $status_id, $public) = $speeltuin->getFields();
		
		$selectedVoorzieningen = $speeltuin->getVoorzieningen();
		$selectedVoorzieningIds = array_keys($selectedVoorzieningen);
		foreach ($allVoorzieningenPop as $voorzieningId => $voorzieningNaam) {
			if (in_array($voorzieningId, $selectedVoorzieningIds)) {
				unset($allVoorzieningenPop[$voorzieningId]);
			}
		}
		foreach ($allVoorzieningenNonPop as $voorzieningId => $voorzieningNaam) {
			if (in_array($voorzieningId, $selectedVoorzieningIds)) {
				unset($allVoorzieningenNonPop[$voorzieningId]);
			}
		}
		
		$photos = $speeltuin->getPhotos();
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