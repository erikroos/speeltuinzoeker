<?php
require_once "../cfg/config.php";
require_once "./inc/functions.php";

$id = get_request_value ( "id", 0 );

$db = new Db ();
$db->connect ();

$speeltuin = new Speeltuin ( $db, $id );

// Stap 1: check of auteur van deze speeltuin, anders heb je hier niets te zoeken
if ($speeltuin->getAuthor () != $_SESSION ["user_id"]) {
	$_SESSION ["feedback"] = "Bekijken van deze speeltuin niet toegestaan.";
	header ( "Location: index.php" );
	exit ();
}

$name = $speeltuin->getName ();
if (empty ( $name )) {
	$name = "speeltuin" . $id;
}

$existingPhotos = $speeltuin->getPhotos ();

if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
	
	// Bepaal hoogste nummer
	$photoNr = 0;
	foreach ( $existingPhotos as $photo ) {
		if (preg_match ( "/.*\/speeltuin\d+_(\d+)\.\w+/", $photo, $matches ) == 1) {
			if ($matches [1] > $photoNr) {
				$photoNr = $matches [1];
			}
		}
	}
	
	if (isset ( $_FILES ["fotos"] )) {
		$allowedExtensions = array (
				"jpeg",
				"jpg",
				"png" 
		);
		
		$nrAdded = 0;
		$uploadErrors = array ();
		foreach ( $_FILES ["fotos"] ["name"] as $i => $fileName ) {
			
			if (empty ( $fileName )) { // ook al upload je niets, er zit altijd één, leeg, element in de array
				continue;
			}
			
			if (sizeof ( $existingPhotos ) + $nrAdded >= MAX_NR_OF_PHOTOS) {
				$uploadErrors [] = "Na toevoegen van " . $nrAdded . " foto('s) is het maximum van " . MAX_NR_OF_PHOTOS . " bestanden bereikt. De rest is niet meer verwerkt.";
				break;
			}
			
			if ($_FILES ["fotos"] ["error"] [$i] == 0) {
				
				$detailError = null;
				$extension = strtolower ( end ( explode ( '.', $fileName ) ) );
				if (! in_array ( $extension, $allowedExtensions )) {
					$detailError = "Fout bij uploaden " . $fileName . ": alleen JP(E)G of PNG toegestaan";
				}
				
				if ($detailError == null) {
					
					Image::resizeAndConvertToPng ( $_FILES ['fotos'] ['tmp_name'] [$i] );
					$photoName = "speeltuin" . $id . "_" . (++ $photoNr) . ".png";
					move_uploaded_file ( $_FILES ["fotos"] ["tmp_name"] [$i], MEDIA_PATH . $photoName );
					$speeltuin->addPhoto ( $photoName );
					$nrAdded ++;
				} else {
					$uploadErrors [] = $detailError;
				}
			} else {
				$uploadErrors [] = "Fout bij uploaden bestand " . $fileName . " (code: " . $_FILES ["fotos"] ["error"] [$i] . ")";
			}
		} // end foreach
		  
		// Is er iets veranderd?
		$newPhotos = $speeltuin->getPhotos ();
		if ($newPhotos == $existingPhotos) {
			
			$_SESSION ["feedback"] = "Foto's niet gewijzigd.<br>";
			if (sizeof ( $uploadErrors ) > 0) {
				$_SESSION ["feedback"] .= "Er hebben zich &eacute;&eacute;n of meer problemen voorgedaan:<br>" . implode ( "<br>", $uploadErrors );
			}
		} else {
			
			// status op 0 zetten (opnieuw beoordelen)
			$speeltuin->setStatus(0);
			
			$_SESSION["feedback"] = "Foto's bewerken gelukt! 
					De speeltuin staat nu tijdelijk op inactief en is niet zichtbaar tot de foto's zijn gecontroleerd. 
					We streven ernaar dit binnen 24 uur te doen.<br>";
			if (sizeof($uploadErrors) > 0) {
				$_SESSION ["feedback"] .= "Er hebben zich wel &eacute;&eacute;n of meer problemen voorgedaan:<br>" . implode("<br>", $uploadErrors);
			}
			
			$message = "<p>Gebruiker " . $_SESSION["user_name"] . " heeft de foto's van de speeltuin \"" . $name . "\" aangepast.</p>" . "<p><a href='" . BASE_URL . "admin/edit.php?id=" . $id . "'>Controleer deze speeltuin</a></p>";
			Mail::sendMail (ADMIN_MAIL, "Foto's speeltuin bewerkt", $message);
		}
	}
	
	header("Location: view.php?user");
	exit();
}

include "tpl/photo.tpl.php";