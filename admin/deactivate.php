<?php
require_once "../cfg/config.php";
require_once "./inc/functions.php";

$id = get_request_value("id", 0);

$db = new Db();
$db->connect();

$user = new User($db, $id);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	$user->deactivate();
	
	$email = $user->getEmail();
	if ($email != null) {
		
		$reason = get_request_value("reason", "");
		
		$message = "<p>Beste " . $user->getName() . ",</p>" .
				"<p>Je account bij Speeltuinzoeker.nl is op non-actief gezet, met als reden: " . $reason . "</p>" .
				"<p>Met vriendelijke groeten,<br>" .
				"Het team van Speeltuinzoeker.nl</p>";
		Mail::sendMail($email, "Je account bij Speeltuinzoeker.nl", $message);
	}
	
	header("users.php?active=0");
}

include "tpl/deactivate.tpl.php";