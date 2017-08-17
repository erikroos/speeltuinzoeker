<?php
class User
{
	private $db;
	private $id;

	function __construct($db = null, $id = 0) {
		$this->db = $db;
		$this->id = $id;
	}

	// Functions that don't need ID:
	
	public function getAllUsers($active = 1, $start = 0, $size = 10) {
		$allUsers = [];
		$res = $this->db->query(sprintf("SELECT * FROM user WHERE active = %d LIMIT %d, %d", $active, $start, $size));
		while ($row = $res->fetch_assoc()) {
			$allUsers[] = $row;
		}
		return $allUsers;
	}
	
	public function getTotalNr($active = 1) {
		$res = $this->db->query(sprintf("SELECT COUNT(*) AS totNr FROM user WHERE active = %d", $active));
		if ($res !== false) {
			if ($row = $res->fetch_assoc()) {
				return $row["totNr"];
			}
		}
		return 0;
	}
	
	// Instance functions:
	
}