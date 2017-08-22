<?php
class Item
{
	private $db;
	private $id;

	function __construct($db = null, $id = 0) {
		$this->db = $db;
		$this->id = $id;
	}

	// Functions that don't need ID:
	
	public function getAllItems() {
		$allItems = [];
		$res = $this->db->query("SELECT * FROM voorziening ORDER BY naam");
		while ($row = $res->fetch_assoc()) {
			$allItems[] = $row;
		}
		return $allItems;
	}
	
	public function getTotalNr() {
		$res = $this->db->query("SELECT COUNT(*) AS totNr FROM voorziening");
		if ($res !== false) {
			if ($row = $res->fetch_assoc()) {
				return $row["totNr"];
			}
		}
		return 0;
	}
	
	// Instance functions:
	
	public function getName() {
		$res = $this->db->query(sprintf("SELECT naam FROM voorziening WHERE id = %d", $this->id));
		if ($res !== false) {
			if ($row = $res->fetch_assoc()) {
				return $row["naam"];
			}
		}
		return "Onbekend";
	}
	
	public function getPopular() {
		$res = $this->db->query(sprintf("SELECT popular FROM voorziening WHERE id = %d", $this->id));
		if ($res !== false) {
			if ($row = $res->fetch_assoc()) {
				return $row["popular"];
			}
		}
		return 0;
	}
	
	public function insertOrUpdate($name, $popular) {
		if ($this->id == 0) {
			$this->db->query(sprintf("INSERT INTO voorziening (naam, popular) VALUES (\"%s\", %d)", 
					$name, $popular));
			$this->id = $this->db->getLatestId();
		} else {
			$this->db->query(sprintf("UPDATE voorziening SET naam = \"%s\", popular = %d WHERE id = %d", 
					$name, $popular, $this->id));
		}
		return $this->id;
	}
	
	public function delete() {
		$this->db->query(sprintf("DELETE FROM voorziening WHERE id = %d", $this->id));
	}
}