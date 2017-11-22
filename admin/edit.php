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
			$_SESSION["feedback"] = "Bekijken/bewerken van deze speeltuin niet toegestaan.";
			header("Location: index.php");
			exit();
		}
	}
	$isUser = true;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	if ($_POST["Submit"] == "Keur goed") {
		
		$speeltuin->activate();
			
		Mail::sendSpeeltuinAcceptedToAuthor($speeltuin);
		
		Twitter::tweet("Nieuwe #speeltuin " . $speeltuin->getName() . ": " . BASE_URL . "speeltuinen/" . $speeltuin->getSeoUrl());
		
		$_SESSION["feedback"] = "Speeltuin goedgekeurd.";
		
		// redirect to last page of actives
		$totalSize = $speeltuin->getTotalNr(1);
		$newStart = $totalSize - ($totalSize % 10);
		if ($newStart >= 10 && $totalSize % 10 == 0) { // correct for start at 0 instead of 1
			$newStart -= 10;
		}
		header("Location: view.php?status=1&start=" . $newStart);
		exit();
	} else if ($_POST["Submit"] == "Keur af") {
		
		$speeltuin->deactivate();
		
		Mail::sendSpeeltuinRejectedToAuthor($speeltuin);
		
		$_SESSION["feedback"] = "Speeltuin afgekeurd.";
		header("Location: view.php?status=2&start=" . $start);
		exit();
	} else {
		
		$name = sanitizeInput(get_request_value("naam", ""));
		$link = sanitizeInput(get_request_value("link", "")); // TODO check validiteit
		$openingstijden = sanitizeInput(get_request_value("openingstijden", ""));
		$vergoeding = sanitizeInput(get_request_value("vergoeding", ""));
		$omschrijving = sanitizeInput(get_request_value("omschrijving", ""));
		$locatieOmschrijving = sanitizeInput(get_request_value("locatie_omschrijving", ""));
		$lat = get_request_value("lat", 0.0);
		$lon = get_request_value("lon", 0.0);
		$public = get_request_value("public", 1);
		$type = get_request_value("speeltuintype", 1);
		$agecat1 = (isset($_POST["agecat_1"]) ? true : false);
		$agecat2 = (isset($_POST["agecat_2"]) ? true : false);
		$agecat3 = (isset($_POST["agecat_3"]) ? true : false);

		$speeltuin->insertOrUpdate($name, $link, $omschrijving, $locatieOmschrijving, $lat, $lon, $public, $type,
            $agecat1, $agecat2, $agecat3, $openingstijden, $vergoeding, $_SESSION["user_id"], $isUser);
		
		if ($id == 0) { // nieuwe, kan alleen door user gebeuren
			$id = $speeltuin->getId();
			$_SESSION["feedback"] = "Speeltuin toegevoegd!<br>
					De speeltuin staat op inactief en is nog niet zichtbaar tot deze gecontroleerd is. 
					We streven ernaar dit binnen 24 uur te doen.";
			Mail::sendSpeeltuinAddedToAdmin($_SESSION["user_name"], $id, (empty($name) ? "zonder naam" : $name));
		} else {
			if ($isUser) {
                $_SESSION["feedback"] = "Speeltuin aanpassen gelukt!<br>
					De speeltuin staat nu tijdelijk op inactief en is niet zichtbaar tot deze gecontroleerd is. 
					We streven ernaar dit binnen 24 uur te doen.";
                Mail::sendSpeeltuinUpdatedToAdmin($_SESSION["user_name"], $id, (empty($name) ? "zonder naam" : $name));
            } else { // admin
                $_SESSION["feedback"] = "Speeltuin aanpassen gelukt!";
            }
		}
		
		$speeltuin->setVoorzieningen($_POST);

		if ($isUser) {
            // redirect to last page of actives for this user
            $totalSize = $speeltuin->getTotalNrForUser($_SESSION["user_id"]);
            $newStart = $totalSize - ($totalSize % 10);
            if ($newStart >= 10 && $totalSize % 10 == 0) { // correct for start at 0 instead of 1
                $newStart -= 10;
            }
            header("Location: view.php?user&start=" . $newStart);
            exit();
        } else {
		    header("Location: view.php?status=" . $speeltuin->getStatusId() . "&start=" . $start);
        }
	}
} else {
	
	$name = "";
	$link = "";
	$openingstijden = "";
	$vergoeding = "";
	$omschrijving = "";
	$locatieOmschrijving = "";
	$lat = 0.0;
	$lon = 0.0;
	$status_id = 0;
	$public = 1;
	$type = "Toestelspeeltuin";
	$agecat1 = false;
	$agecat2 = true;
	$agecat3 = true;
	
	$allVoorzieningenPop = $speeltuin->getAllVoorzieningen(1);
	$allVoorzieningenNonPop = $speeltuin->getAllVoorzieningen(0);
	$selectedVoorzieningen = [];
	$photos = [];
	
	if ($id > 0) { // bestaand
		list($name, $link, $omschrijving, $locatieOmschrijving, $lat, $lon, $status_id, $public, $type,
            $agecat1, $agecat2, $agecat3, $openingstijden, $vergoeding) = $speeltuin->getFields();
		
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
	$pageTitle = "Voeg speeltuin toe";
} else {
	if ($isUser) {
		$pageTitle = "Bekijk/bewerk speeltuin " . $name;
	} else { // admin
		if ($status_id == 0) {
			$pageTitle = "Bekijk/bewerk of keur speeltuin " . $name . " goed of af";
		} else if ($status_id == 1 || $status_id == 2) {
			$pageTitle = "Bekijk/bewerk speeltuin " . $name;
		}
	}
}

include "tpl/edit.tpl.php";