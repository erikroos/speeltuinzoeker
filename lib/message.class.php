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
	
	public function getMostRecentMessage($userId) {
		$res = $this->db->query("SELECT id, body FROM bericht ORDER BY created_on DESC LIMIT 1");
		if ($res !== false) {
			if ($row = $res->fetch_assoc()) {
				$res2 = $this->db->query(sprintf("SELECT * FROM bericht_gelezen WHERE bericht_id = %d AND user_id = %d", $row["id"], $userId));
				if (!$res2 || !$res2->fetch_assoc()) {
					return array($row["id"], $row["body"]);
				}
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
	
	public function getBody() {
		$res = $this->db->query(sprintf("SELECT body FROM bericht WHERE id = %d", $this->id));
		if ($res !== false) {
			if ($row = $res->fetch_assoc()) {
				return $row["body"];
			}
		}
		return 0;
	}
	
	public function insertOrUpdate($body) {
		if ($this->id == 0) {
			$this->db->query(sprintf("INSERT INTO bericht (body, created_on) VALUES (\"%s\", NOW())",
                $this->db->realEscapeString($body)));
			$this->id = $this->db->getLatestId();
		} else {
			$this->db->query(sprintf("UPDATE bericht SET body = \"%s\", created_on = NOW() WHERE id = %d",
                $this->db->realEscapeString($body), $this->id));
		}
		return $this->id;
	}
	
	public function delete() {
		$this->db->query(sprintf("DELETE FROM bericht WHERE id = %d", $this->id));
	}
	
	public function markAsRead($userId) {
		$this->db->query(sprintf("INSERT INTO bericht_gelezen (bericht_id, user_id) VALUES (%d, %d)", $this->id, $userId));
	}
}