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
		} else {
			echo $this->db->getError();die;
		}
		return null;
	}
	
	// Instance functions:
	
}