<?php
class Speeltuin
{
    private $db;
    private $id;

    function __construct($db = null, $id = 0) {
        $this->db = $db;
        $this->id = $id;
    }

    // Functions that don't need ID:
    
    public function toSeoUrl($name) {
    	$seoUrl = strtolower($name);
    	$seoUrl = str_replace(" ", "-", $seoUrl);
    	$seoUrl = preg_replace("/[^a-z0-9\-]/", "", $seoUrl);
    	return $seoUrl;
    }
    
    public function isExistingId($idToCheck) {
    	$res = $this->db->query(sprintf("SELECT * FROM speeltuin WHERE id = %d", $idToCheck));
    	if ($res != null && $row = $res->fetch_assoc()) {
    		return true;
    	}
    	return false;
    }

    public function getAllSpeeltuinen() {
        $allSpeeltuinen = [];
        $res = $this->db->query("SELECT * FROM speeltuin WHERE status_id = 1");
        while ($row = $res->fetch_assoc()) {
            $allSpeeltuinen[] = $row;
        }
        return $allSpeeltuinen;
    }
    
    public function getAllSpeeltuinenAtoZ() {
    	$allSpeeltuinen = [];
    	$res = $this->db->query("SELECT LEFT(LOWER(REPLACE(naam, \"'\", \"\")), 1) AS first_letter, speeltuin.* FROM speeltuin WHERE status_id = 1 ORDER BY first_letter");
    	while ($row = $res->fetch_assoc()) {
    		$allSpeeltuinen[$row["first_letter"]][] = $row;
    	}
    	return $allSpeeltuinen;
    }

    public function search($q) {
        $allSpeeltuinen = [];
        $q = strtolower($q);
        $res = $this->db->query("SELECT * FROM speeltuin WHERE status_id = 1 AND (LOWER(naam) LIKE \"%" . $q . "%\" OR LOWER(omschrijving) LIKE \"%" . $q . "%\" OR LOWER(locatie_omschrijving) LIKE \"%" . $q . "%\") ORDER BY naam");
        while ($row = $res->fetch_assoc()) {
            $allSpeeltuinen[] = $row;
        }
        return $allSpeeltuinen;
    }
    
    public function getAllSpeeltuinenInBoundingBox($neLat, $neLon, $swLat, $swLon, $type = null, $agecat = null, $access = null, $voorziening = null, $minRating = 0) {
    	$allSpeeltuinen = [];
    	
    	$typeClause = "";
    	if ($type != null) {
    		$typeClause = "AND speeltuintype IN (\"" . implode("\",\"", $type) . "\")";
    	}
    	
    	$ageClause = "";
    	if ($agecat != null) {
    		if (sizeof($agecat) == 1) {
    			$ageClause = "AND " . $agecat[0] . " = 1";
    		} else {
    			$ageClause = "AND (";
	    		foreach ($agecat as $agecatColname) {
	    			$ageClause .= ($agecatColname . " = 1 OR ");
	    		}
	    		$ageClause = substr($ageClause, 0, -4);
	    		$ageClause .= ")";
    		}
    	}
    	
    	$accessClause = "";
    	if ($access != null) {
    		if (sizeof($access) == 1) {
    			$accessClause = "AND public = " . $access[0];
    		} else {
    			$accessClause = "AND (";
	    		foreach ($access as $accessId) {
	    			$accessClause .= ("public = " . $accessId . " OR ");
	    		}
	    		$accessClause = substr($accessClause, 0, -4);
	    		$accessClause .= ")";
    		}
    	}
    	
    	$query = sprintf("SELECT id, naam, speeltuintype, omschrijving, lat, lon, public, seo_url, avg_rating, times_rated FROM speeltuin
			WHERE status_id = 1
			AND lat <= %s AND lat >= %s AND lon >= %s AND lon <= %s
    		%s %s %s
    		AND avg_rating >= %f",
    		$neLat, $swLat, $swLon, $neLon, $typeClause, $ageClause, $accessClause, $minRating);
    	$res = $this->db->query($query);
    	if ($res !== false) {
            while ($row = $res->fetch_assoc()) {
                // Na-filtering op voorziening
                if ($voorziening != null) {
                    foreach ($voorziening as $voorzieningId) {
                        $res2 = $this->db->query(sprintf("SELECT * FROM speeltuin_voorziening WHERE speeltuin_id = %d AND voorziening_id = %d", $row["id"], $voorzieningId));
                        if ($res2 === false || $res2->num_rows == 0) {
                            continue 2; // while
                        }
                    }
                }
				// Paar kolommen verbouwen
                if (strlen($row["omschrijving"]) > 100) {
                    $row["omschrijving"] = substr($row["omschrijving"], 0, 100) . "...";
                }
                $accessOptions = $this->getAllAccessOptions(true);
                $row["publicString"] = $accessOptions[$row["public"]];
                
                $allSpeeltuinen[] = $row;
            }
        }
    	return $allSpeeltuinen;
    }

    public function getAllVoorzieningen($showPopular = 2) {
        $allVoorzieningen = [];
        
        $whereClause = "";
        if ($showPopular == 0 || $showPopular == 1) {
        	$whereClause = " WHERE popular = " . $showPopular;
        }
        
        $res = $this->db->query(sprintf("SELECT id, naam FROM voorziening%s ORDER BY naam", $whereClause));
        if ($res !== false) {
            while ($row = $res->fetch_assoc()) {
                $allVoorzieningen[$row["id"]] = $row["naam"];
            }
        }
        return $allVoorzieningen;
    }

    public function getTotalNr($status = 1) {
	    $res = $this->db->query(sprintf("SELECT COUNT(*) AS totNr FROM speeltuin WHERE status_id = %d", $status));
        if ($res !== false) {
            if ($row = $res->fetch_assoc()) {
                return $row["totNr"];
            }
        }
	    return 0;
    }
    
    public function getTotalNrForUser($userId) {
    	$res = $this->db->query(sprintf("SELECT COUNT(*) AS totNr FROM speeltuin WHERE author_id = %d", $userId));
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
    
    public function getAllTypes() {
    	// TODO koppeling met enum in DB
    	return ["Toestelspeeltuin", "Natuurspeeltuin", "Kinderboerderij", "Kinderboerderij met speeltuin", "Sportfaciliteit"];
    }
    
    public function getAllAgecats() {
    	return ["agecat_1" => "Leuk voor de allerkleinsten", "agecat_2" => "Leuk voor de jonge jeugd", "agecat_3" => "Leuk voor de wat oudere jeugd"];
    }
    
    public function getAllAccessOptions($paidAllowed = false) {
    	if ($paidAllowed) {
    		return [0 => "Betaald", 1 => "Gratis en altijd toegankelijk", 2 => "Beperkt toegankelijk"];
    	} else {
    		return [1 => "Gratis en altijd toegankelijk", 2 => "Beperkt toegankelijk"];
    	}
    }
    
    public function getIdBySeoUrl($seoUrl) {
    	$res = $this->db->query(sprintf("SELECT id FROM speeltuin WHERE seo_url = \"%s\"", $seoUrl));
    	if ($row = $res->fetch_assoc()) {
    		return $row["id"];
    	}
    	return 0;
    }

    // Instance functions:
	
	public function getId() {
		return $this->id;
	}
	
	public function getName() {
		$res = $this->db->query(sprintf("SELECT naam FROM speeltuin WHERE id = %d", $this->id));
		if ($row = $res->fetch_assoc()) {
			return $row["naam"];
		}
		return "";
	}
	
	public function getSeoUrl() {
		$res = $this->db->query(sprintf("SELECT seo_url FROM speeltuin WHERE id = %d", $this->id));
		if ($row = $res->fetch_assoc()) {
			return $row["seo_url"];
		}
		return null;
	}
	
	public function getLink() {
		$res = $this->db->query(sprintf("SELECT link FROM speeltuin WHERE id = %d", $this->id));
		if ($row = $res->fetch_assoc()) {
			return $row["link"];
		}
		return null;
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
		$res = $this->db->query(sprintf("SELECT locatie_omschrijving FROM speeltuin WHERE id = %d", $this->id));
		if ($row = $res->fetch_assoc()) {
			return $row["locatie_omschrijving"];
		}
		return "";
	}
	
	public function getType() {
		$res = $this->db->query(sprintf("SELECT speeltuintype FROM speeltuin WHERE id = %d", $this->id));
		if ($row = $res->fetch_assoc()) {
			return $row["speeltuintype"];
		}
		return "";
	}
	
	public function getAgecatString() {
		$res = $this->db->query(sprintf("SELECT agecat_1, agecat_2, agecat_3 FROM speeltuin WHERE id = %d", $this->id));
		if ($row = $res->fetch_assoc()) {
			$cats = [];
			if ($row["agecat_1"] == 1) {
				$cats[] = "de allerkleinsten";
			}
			if ($row["agecat_2"] == 1) {
				$cats[] = "de jonge jeugd";
			}
			if ($row["agecat_3"] == 1) {
				$cats[] = "de wat oudere jeugd";
			}
			return "Leuk voor " . implode(", ", $cats);
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
		$res = $this->db->query(sprintf("SELECT naam FROM user WHERE id = %d", $authorId));
		if ($res !== false) {
			if ($row = $res->fetch_assoc()) {
				return $row["naam"];
			}
		}
		return "Onbekend";
	}
	
	public function getAuthorEmail() {
		$authorId = $this->getAuthor();
		$res = $this->db->query(sprintf("SELECT email FROM user WHERE id = %d", $authorId));
		if ($res !== false) {
			if ($row = $res->fetch_assoc()) {
				return $row["email"];
			}
		}
		return null;
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
				$accessOptions = $this->getAllAccessOptions(true);
				if ($row["public"] >= 0 && $row["public"] <= 2) {
					return $accessOptions[$row["public"]];
				} else {
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

	public function insertOrUpdate($name, $link, $omschrijving, $locatieOmschrijving, $lat, $lon, $public, $type,
                                   $agecat1, $agecat2, $agecat3, $openingstijden, $vergoeding, $userId, $isUser) {
		if ($this->id == 0) {
			$this->db->query(sprintf("INSERT INTO speeltuin 
					(naam, link, omschrijving, locatie_omschrijving, lat, lon, status_id, author_id, `public`, 
					speeltuintype, agecat_1, agecat_2, agecat_3, seo_url, modified_on, openingstijden, vergoeding)
					VALUES (\"%s\", \"%s\", \"%s\", \"%s\", %f, %f, 0, %d, %d, \"%s\", %d, %d, %d, \"%s\", NOW(), \"%s\", \"%s\")",
					$name, $link, $omschrijving, $locatieOmschrijving, $lat, $lon, $userId, $public, 
					$type, $agecat1 ? 1 : 0, $agecat2 ? 1 : 0, $agecat3 ? 1 : 0, $this->toSeoUrl($name), $openingstijden, $vergoeding));
			$this->id = $this->db->getLatestId();
		} else {
			$this->db->query(sprintf("UPDATE speeltuin
				SET naam = \"%s\", link = \"%s\", omschrijving = \"%s\", locatie_omschrijving = \"%s\", lat = %f, lon = %f, 
					`public` = %d, speeltuintype = \"%s\", agecat_1 = %d, agecat_2 = %d, agecat_3 = %d, seo_url = \"%s\", 
					modified_on = NOW(), openingstijden = \"%s\", vergoeding = \"%s\"
				WHERE id = %d", $name, $link, $omschrijving, $locatieOmschrijving, $lat, $lon, $public, $type, 
					$agecat1 ? 1 : 0, $agecat2 ? 1 : 0, $agecat3 ? 1 : 0, $this->toSeoUrl($name), $openingstijden, $vergoeding, $this->id));
			if ($isUser) { // user -> op inactief, admin -> laat zoals het was
                $this->db->query(sprintf("UPDATE speeltuin SET status_id = 0 WHERE id = %d", $this->id));
            }
		}
		return $this->id;
	}

	public function getVoorzieningen() {
		$selectedVoorzieningen = [];
		$res = $this->db->query(sprintf("SELECT voorziening_id, voorziening.naam
				FROM speeltuin_voorziening
				JOIN voorziening ON speeltuin_voorziening.voorziening_id = voorziening.id
				WHERE speeltuin_id = %d", $this->id));
		if ($res !== false) {
			while ($row = $res->fetch_assoc()) {
				$selectedVoorzieningen[$row["voorziening_id"]] = $row["naam"];
			}
		}
		return $selectedVoorzieningen;
	}

	public function setVoorzieningen($postVars) {
		$this->db->query(sprintf("DELETE FROM speeltuin_voorziening WHERE speeltuin_id = %d", $this->id));
		foreach ($this->getAllVoorzieningen () as $voorzieningId => $voorzieningNaam) {
			if (isset($postVars["v" . $voorzieningId])) {
				$this->db->query(sprintf("INSERT INTO speeltuin_voorziening (speeltuin_id, voorziening_id) 
						VALUES (%d, %d)", $this->id, $voorzieningId));
			}
		}
	}

	public function getFields() {
		$res = $this->db->query(sprintf("SELECT * FROM speeltuin WHERE id = %d", $this->id));
		if ($res !== false) {
			if ($row = $res->fetch_assoc()) {
				return array(
						$row["naam"],
						$row["link"],
						$row["omschrijving"],
						$row["locatie_omschrijving"],
						$row["lat"],
						$row["lon"],
						$row["status_id"],
						$row["public"],
						$row["speeltuintype"],
						$row["agecat_1"],
						$row["agecat_2"],
						$row["agecat_3"],
                        $row["openingstijden"],
                        $row["vergoeding"]
				);
			}
		}
		return array(
				"",
				"",
				"",
				"",
				"",
				0,
				1,
				"Toestelspeeltuin",
				false,
				true,
				true,
                "",
                ""
		);
	}

	public function getPhotos() {
		$photos = [];
		$res = $this->db->query(sprintf("SELECT url FROM bestand
				JOIN speeltuin_bestand ON bestand.id = speeltuin_bestand.bestand_id
				WHERE speeltuin_bestand.speeltuin_id = %d", $this->id));
		while ($row = $res->fetch_assoc()) {
			$photos[] = $row["url"];
		}
		return $photos;
	}

	public function addPhoto($photoName) {
		$this->db->query(sprintf("INSERT INTO bestand (naam, full_path, url, extensie)
									VALUES (\"%s\", \"%s\", \"%s\", \"png\")", $photoName, MEDIA_PATH . $photoName, MEDIA_URL . $photoName));
		$bestandId = $this->db->getLatestId();
		$this->db->query(sprintf("INSERT INTO speeltuin_bestand (speeltuin_id, bestand_id)
									VALUES (%d, %d)", $this->id, $bestandId));
	}

	public function removePhoto($photoName) {
		$res = $this->db->query(sprintf("SELECT id FROM bestand WHERE naam = \"%s\"", $photoName));
		if ($res !== false) {
			if ($row = $res->fetch_assoc()) {
				$this->db->query(sprintf("DELETE FROM bestand WHERE id = %d", $row["id"]));
				$this->db->query(sprintf("DELETE FROM speeltuin_bestand WHERE bestand_id = %d", $row["id"]));
			}
		}
	}

	public function setStatus($statusId) {
		$this->db->query(sprintf("UPDATE speeltuin SET status_id = %d WHERE id = %d", $statusId, $this->id));
	}

	public function getStatusId() {
        $res = $this->db->query(sprintf("SELECT status_id FROM speeltuin WHERE id = %d", $this->id));
        if ($res !== false) {
            if ($row = $res->fetch_assoc()) {
                return $row["status_id"];
            }
        }
        return 0;
    }
	
	public function delete() {
		$this->db->query(sprintf("DELETE FROM speeltuin WHERE id = %d", $this->id));
		$this->db->query(sprintf("DELETE FROM speeltuin_voorziening WHERE speeltuin_id = %d", $this->id));
		// Files, also on disk:
		$res = $this->db->query(sprintf("SELECT bestand_id FROM speeltuin_bestand WHERE speeltuin_id = %d", $this->id));
		if ($res !== false) {
			while ($row = $res->fetch_assoc()) {
				$bestandId = $row["bestand_id"];
				$res2 = $this->db->query(sprintf("SELECT full_path FROM bestand WHERE id = %d", $bestandId));
				if ($row2 = $res2->fetch_assoc()) {
					unlink($row2["full_path"]);
				}
				$this->db->query(sprintf("DELETE FROM bestand WHERE id = %d", $bestandId));
				$this->db->query(sprintf("DELETE FROM speeltuin_bestand WHERE bestand_id = %d", $bestandId));
			}
		}
	}
	
	public function getRating() {
		$res = $this->db->query(sprintf("SELECT times_rated, avg_rating FROM speeltuin WHERE id = %d", $this->id));
        if ($res !== false) {
            if ($row = $res->fetch_assoc()) {
                return $row;
            }
        }
		return null;
	}

    public function getOpeningstijden() {
        $res = $this->db->query(sprintf("SELECT openingstijden FROM speeltuin WHERE id = %d", $this->id));
        if ($row = $res->fetch_assoc()) {
            return $row["openingstijden"];
        }
        return "";
    }

    public function getVergoeding() {
        $res = $this->db->query(sprintf("SELECT vergoeding FROM speeltuin WHERE id = %d", $this->id));
        if ($row = $res->fetch_assoc()) {
            return $row["vergoeding"];
        }
        return "";
    }
}
