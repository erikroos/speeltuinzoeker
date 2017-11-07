<?php
session_start();

require_once "./cfg/config.php";
require_once "./admin/inc/functions.php";

$db = new Db();
$db->connect();

$speeltuin = new Speeltuin($db);

$id = get_request_value("speeltuin", 0);
if (is_numeric($id)) {
    if ($id <= 0) {
        die("Ongeldig ID!");
    }
} else if (is_string($id)) {
    $id = $speeltuin->getIdBySeoUrl($id);
    if ($id == 0) {
    	die("Ongeldige naam!");
    }
} else {
    die("Ongeldig(e) ID/naam!");
}

// Opnieuw, nu mét ID:
$speeltuin = new Speeltuin($db, $id);

if (!$speeltuin->isExistingId($id)) {
	die("Niet-bestaande speeltuin!");
}

$photos = $speeltuin->getPhotos();

$alleVoorzieningen = $speeltuin->getAllVoorzieningen();

$rating = $speeltuin->getRating();

$review = new Review($db);
$reviews = $review->getAllReviewsForSpeeltuin($id);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (isset($_SESSION["user_id"]) && $_SESSION["user_id"] == $_POST["userId"]) {
		
		$poster = new User($db, $_POST["userId"]);
		$comment = sanitizeInput($_POST["comment"]);
		
		if (isset($_POST["rating"])) { // beoordeling
			if (!empty($comment) && $_POST["rating"] > 0) {
				$review->insertOrUpdate($id, $_POST["rating"], $comment, $_POST["userId"]);
				$reviewed = true;
			} else {
				$reviewed = false;
			}
		} else { // wijzigingsverzoek
			if (!empty($comment)) {
	            Mail::sendUpdateRequestToAuthor($speeltuin, $poster, $id, $comment);
	            $sent = true;
	        } else {
	            $sent = false;
	        }
		}
		
	} else {
		$sent = false;
	}
}

include "tpl/detail.tpl.php";