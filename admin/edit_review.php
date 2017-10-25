<?php
require_once "../cfg/config.php";
require_once "./inc/functions.php";

$id = get_request_value("id", 0);
$start = get_request_value("start", 0);

$db = new Db();
$db->connect();

$review = new Review($db, $id);

$speeltuin = new Speeltuin($db);

// Stap 1: check of admin of auteur van deze beoordeling, anders heb je hier niets te zoeken
$isUser = false;
$isAdmin = false;
if ($_SESSION["admin"] == 1) {
	$isAdmin = true;
} else {
	if ($id > 0) {
		if ($review->getAuthor() != $_SESSION["user_id"]) {
			$_SESSION["feedback"] = "Bekijken/bewerken van deze beoordeling niet toegestaan.";
			header("Location: index.php");
			exit();
		}
	}
	$isUser = true;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	if ($_POST["Submit"] == "Keur goed") {
		
		$review->activate();
		
		// 1. mail aan auteur van de beoordeling
		Mail::sendReviewAcceptedToAuthor($review);
		
		// 2. mail aan auteur van de speeltuin
		Mail::sendReviewAcceptedToSpeeltuinAuthor($review);
		
		$_SESSION["feedback"] = "Beoordeling goedgekeurd.";
		
		// redirect to last page of actives
		$totalSize = $review->getTotalNr(1);
		$newStart = $totalSize - ($totalSize % 10);
		if ($newStart >= 10 && $totalSize % 10 == 0) { // correct for start at 0 instead of 1
			$newStart -= 10;
		}
		header("Location: review.php?status=1&start=" . $newStart);
		exit();
	} else if ($_POST["Submit"] == "Keur af") {
		
		$review->deactivate(false);
		
		Mail::sendReviewRejectedToAuthor($review, $_POST["afkeur_reden"]);
		
		$_SESSION["feedback"] = "Beoordeling afgekeurd.";
		header("Location: review.php?status=2&start=" . $start);
		exit();
	} else {
		
		$comment = sanitizeInput($_POST["comment"]);
		
		$review->insertOrUpdate($_POST["speeltuinId"], $_POST["rating"], $comment, $_SESSION["user_id"]);
		
		$_SESSION["feedback"] = "Beoordeling aanpassen gelukt!<br>
				De beoordeling staat nu tijdelijk op inactief en is niet zichtbaar tot deze gecontroleerd is. 
				We streven ernaar dit binnen 24 uur te doen.";
		
		Mail::sendUpdatedReviewToAdmin($review);
		
		// redirect to last page of actives for this user
		$totalSize = $review->getTotalNrForUser($_SESSION["user_id"]);
		$newStart = $totalSize - ($totalSize % 10);
		if ($newStart >= 10 && $totalSize % 10 == 0) { // correct for start at 0 instead of 1
			$newStart -= 10;
		}
		header("Location: review.php?user&start=" . $newStart);
		exit();
	}
} else {
	list($speeltuinId, $rating, $comment, $status) = $review->getFields();
}

if ($isUser) {
	$pageTitle = "Bewerk beoordeling";
} else { // admin
	if ($status == 0) {
		$pageTitle = "Keur beoordeling goed of af";
	} else if ($status == 1 || $status == 2) {
		$pageTitle = "Bekijk beoordeling";
	}
}

include "tpl/edit_review.tpl.php";