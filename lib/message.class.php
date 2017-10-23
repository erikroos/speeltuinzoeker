<?php
class Message
{
	private $db;
	private $id;

	function __construct($db = null, $id = 0) {
		$this->db = $db;
		$this->id = $id;
	}

	// Functions that don't need ID:
	
	public function getMostRecentMessage() {
		$res = $this->db->query("SELECT body FROM bericht ORDER BY created_on DESC LIMIT 1");
		if ($res !== false) {
			if ($row = $res->fetch_assoc()) {
				return $row["body"];
			}
		}
		return null;
	}
	
	public function getMessages($start = 0, $size = 10) {
		$messages = [];
		$res = $this->db->query(sprintf("SELECT * FROM bericht ORDER BY created_on DESC LIMIT %d, %d", $start, $size));
		if ($res !== false) {
			while ($row = $res->fetch_assoc()) {
				$messages[] = $row;
			}
		}
		return $messages;
	}
	
	public function getTotalNr() {
		$res = $this->db->query("SELECT COUNT(*) AS totNr FROM bericht");
		if ($res !== false) {
			if ($row = $res->fetch_assoc()) {
				return $row["totNr"];
			}
		}
		return 0;
	}
	
	// Instance functions:
	
}