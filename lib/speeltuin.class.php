<?php
class Speeltuin
{
    private $db;
    private $id;

    function __construct($db = null, $id = 0)
    {
        $this->db = $db;
        $this->id = $id;
    }

    // Functions that don't need ID:

    public function getAllSpeeltuinen() {
        $allSpeeltuinen = [];
        $res = $this->db->query("SELECT * FROM speeltuin WHERE status_id = 1");
        while ($row = $res->fetch_assoc()) {
            $allSpeeltuinen[] = $row;
        }
        return $allSpeeltuinen;
    }

    public function getAllVoorzieningen() {
        $allVoorzieningen = [];
        $res = $this->db->query("SELECT id, naam FROM voorziening ORDER BY naam");
        if ($res !== false) {
            while ($row = $res->fetch_assoc()) {
                $allVoorzieningen[$row["id"]] = $row["naam"];
            }
        }
        return $allVoorzieningen;
    }

    public function getTotalNr() {
	    $res = $this->db->query("SELECT COUNT(*) AS totNr FROM speeltuin WHERE status_id = 1");
        if ($res !== false) {
            if ($row = $res->fetch_assoc()) {
                return $row["totNr"];
            }
        }
	    return 0;
    }

    public function getLatestEntry() {
        $res = $this->db->query("SELECT * FROM speeltuin WHERE status_id = 1 ORDER BY id DESC LIMIT 1");
        if ($res !== false) {
            if ($row = $res->fetch_assoc()) {
                return $row;
            }
        }
        return null;
    }

    // Instance functions:
	
	public function getId() {
		return $this->id;
	}
	
	public function getName() {
		$res = $this->db->query ( sprintf ( "SELECT naam FROM speeltuin WHERE id = %d", $this->id ) );
		if ($row = $res->fetch_assoc ()) {
			return $row ["naam"];
		}
		return "";
	}
	
	public function getDescription() {
		$res = $this->db->query ( sprintf ( "SELECT omschrijving FROM speeltuin WHERE id = %d", $this->id ) );
		if ($row = $res->fetch_assoc ()) {
			return $row ["omschrijving"];
		}
		return "";
	}

	public function getLatitude() {
		$res = $this->db->query ( sprintf ( "SELECT lat FROM speeltuin WHERE id = %d", $this->id ) );
		if ($row = $res->fetch_assoc ()) {
			return $row ["lat"];
		}
		return "";
	}

	public function getLongitude() {
		$res = $this->db->query ( sprintf ( "SELECT lon FROM speeltuin WHERE id = %d", $this->id ) );
		if ($row = $res->fetch_assoc ()) {
			return $row ["lon"];
		}
		return "";
	}

	public function getLocationDescription() {
		$res = $this->db->query ( sprintf ( "SELECT locatie_omschrijving FROM speeltuin WHERE id = %d", $this->id ) );
		if ($row = $res->fetch_assoc ()) {
			return $row ["locatie_omschrijving"];
		}
		return "";
	}

	public function getAuthor() {
		$res = $this->db->query(sprintf("SELECT author_id FROM speeltuin WHERE id = %d", $this->id));
		if ($res !== false) {
			if ($row = $res->fetch_assoc()) {
				return $row["author_id"];
			}
		}
		return 0;
	}
	
	public function getAuthorName() {
		$authorId = $this->getAuthor();
		$res = $this->db->query(sprintf("SELECT name FROM user WHERE id = %d", $authorId));
		if ($res !== false) {
			if ($row = $res->fetch_assoc()) {
				return $row["name"];
			}
		}
		return "Onbekend";
	}
	
	public function getLastModified() {
		$res = $this->db->query(sprintf("SELECT modified_on FROM speeltuin WHERE id = %d", $this->id));
		if ($res !== false) {
			if ($row = $res->fetch_assoc()) {
				setlocale(LC_TIME, "nl_NL");
				return strftime("%A %#d %B %Y om %H:%M", strtotime($row["modified_on"]));
			}
		}
		return "-";
	}
	
	public function getPublic() {
		$res = $this->db->query(sprintf("SELECT public FROM speeltuin WHERE id = %d", $this->id));
		if ($res !== false) {
			if ($row = $res->fetch_assoc()) {
				switch ($row["public"]) {
					case 0:
						return "Betaald";
					case 1:
						return "Gratis en altijd toegankelijk";
					case 2:
						return "Gratis maar beperkt toegankelijk";
					default:
						return "Onbekend";
				}
			}
		}
		return "Onbekend";
	}

	public function activate() {
		$this->db->query(sprintf("UPDATE speeltuin SET status_id = 1 WHERE id = %d", $this->id));
	}

	public function deactivate() {
		$this->db->query(sprintf("UPDATE speeltuin SET status_id = 2 WHERE id = %d", $this->id));
	}

	public function insertOrUpdate($name, $omschrijving, $locatieOmschrijving, $lat, $lon, $public = 1) {
		if ($this->id == 0) {
			$this->db->query(sprintf("INSERT INTO speeltuin (naam, omschrijving, locatie_omschrijving, lat, lon, status_id, author_id, public, modified_on)
					VALUES (\"%s\", \"%s\", \"%s\", %f, %f, 0, %d, %d, NOW())", $name, $omschrijving, $locatieOmschrijving, $lat, $lon, $_SESSION["user_id"], $public));
			$this->id = $this->db->getLatestId ();
		} else {
			$this->db->query(sprintf("UPDATE speeltuin
				SET naam = \"%s\", omschrijving = \"%s\", locatie_omschrijving = \"%s\", lat = %f, lon = %f, status_id = 0, public = %d, modified_on = NOW()
				WHERE id = %d", $name, $omschrijving, $locatieOmschrijving, $lat, $lon, $public, $this->id));
		}
		return $this->id;
	}

	public function getVoorzieningen() {
		$selectedVoorzieningen = [ ];
		$res = $this->db->query ( sprintf ( "SELECT voorziening_id FROM speeltuin_voorziening WHERE speeltuin_id = %d", $this->id ) );
		if ($res !== false) {
			while ( $row = $res->fetch_assoc () ) {
				$selectedVoorzieningen [] = $row ["voorziening_id"];
			}
		}
		return $selectedVoorzieningen;
	}

	public function setVoorzieningen($postVars) {
		$this->db->query ( sprintf ( "DELETE FROM speeltuin_voorziening WHERE speeltuin_id = %d", $this->id ) );
		foreach ( $this->getAllVoorzieningen () as $voorzieningId => $voorzieningNaam ) {
			if (isset ( $postVars ["v" . $voorzieningId] )) {
				$this->db->query ( sprintf ( "INSERT INTO speeltuin_voorziening (speeltuin_id, voorziening_id) 
						VALUES (%d, %d)", $this->id, $voorzieningId ) );
			}
		}
	}

	public function getFields() {
		$res = $this->db->query(sprintf("SELECT * FROM speeltuin WHERE id = %d", $this->id));
		if ($res !== false) {
			if ($row = $res->fetch_assoc()) {
				return array (
						$row["naam"],
						$row["omschrijving"],
						$row["locatie_omschrijving"],
						$row["lat"],
						$row["lon"],
						$row["status_id"],
						$row["public"]
				);
			}
		}
		return array (
				"",
				"",
				"",
				"",
				"",
				0,
				1
		);
	}

	public function getPhotos() {
		$photos = [ ];
		$res = $this->db->query ( sprintf ( "SELECT url FROM bestand
				JOIN speeltuin_bestand ON bestand.id = speeltuin_bestand.bestand_id
				WHERE speeltuin_bestand.speeltuin_id = %d", $this->id ) );
		while ( $row = $res->fetch_assoc () ) {
			$photos [] = $row ["url"];
		}
		return $photos;
	}

	public function addPhoto($photoName) {
		$this->db->query ( sprintf ( "INSERT INTO bestand (naam, full_path, url, extensie)
									VALUES (\"%s\", \"%s\", \"%s\", \"png\")", $photoName, MEDIA_PATH . $photoName, MEDIA_URL . $photoName ) );
		$bestandId = $this->db->getLatestId ();
		$this->db->query ( sprintf ( "INSERT INTO speeltuin_bestand (speeltuin_id, bestand_id)
									VALUES (%d, %d)", $this->id, $bestandId ) );
	}

	public function removePhoto($photoName) {
		$res = $this->db->query ( sprintf ( "SELECT id FROM bestand WHERE naam = \"%s\"", $photoName ) );
		if ($res !== false) {
			if ($row = $res->fetch_assoc ()) {
				$this->db->query ( sprintf ( "DELETE FROM bestand WHERE id = %d", $row ["id"] ) );
				$this->db->query ( sprintf ( "DELETE FROM speeltuin_bestand WHERE bestand_id = %d", $row ["id"] ) );
			}
		}
	}

	public function setStatus($statusId) {
		$this->db->query ( sprintf ( "UPDATE speeltuin SET status_id = %d WHERE id = %d", $statusId, $this->id ) );
	}
}
