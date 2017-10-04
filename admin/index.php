<?php
require_once "../cfg/config.php";
require_once "./inc/functions.php";

$db = new Db();
$db->connect();

$speeltuin = new Speeltuin($db);
$user = new User($db);
$item = new Item($db);
$review = new Review($db);

$nrActive = $speeltuin->getTotalNr(1);
$nrProposed = $speeltuin->getTotalNr(0);
$nrRejected = $speeltuin->getTotalNr(2);

$nrActiveReviews = $review->getTotalNr(1);
$nrProposedReviews = $review->getTotalNr(0);
$nrRejectedReviews = $review->getTotalNr(2);

$nrOfUsers = $user->getTotalNr(1);
$nrOfItems = $item->getTotalNr();

// most recent message
$msgBody = null;
if (!isset($_SESSION["admin"]) || $_SESSION["admin"] == 0) {
	$msg = new Message($db);
	$msgBody = $msg->getMostRecentMessage();
}

include "tpl/index.tpl.php";