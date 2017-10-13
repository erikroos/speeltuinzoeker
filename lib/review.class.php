<?php
class Review
{
	private $db;
	private $id;

	function __construct($db = null, $id = 0) {
		$this->db = $db;
		$this->id = $id;
	}

	// Functions that don't need ID:
	
	public function getTotalNr($status = 1) {
		$res = $this->db->query(sprintf("SELECT COUNT(*) AS totNr FROM review WHERE status = %d", $status));
		if ($res !== false) {
			if ($row = $res->fetch_assoc()) {
				return $row["totNr"];
			}
		}
		return 0;
	}
	
	public function getTotalNrForUser($userId) {
		$res = $this->db->query(sprintf("SELECT COUNT(*) AS totNr FROM review WHERE user_id = %d", $userId));
		if ($res !== false) {
			if ($row = $res->fetch_assoc()) {
				return $row["totNr"];
			}
		}
		return 0;
	}
	
	public function getAllReviewsForSpeeltuin($speeltuinId) {
		$reviews = [];
		$res = $this->db->query(sprintf("SELECT review.*, user.naam FROM review 
				JOIN user ON review.user_id = user.id
				WHERE status = 1 AND speeltuin_id = %d", $speeltuinId));
		if ($res !== false) {
			while ($row = $res->fetch_assoc()) {
				$reviews[] = $row;
			}
		}
		return $reviews;
	}
	
	// Instance functions:
	
	public function getStatus() {
		$status = 0;
		$res = $this->db->query(sprintf("SELECT status FROM review WHERE id = %d", $this->id));
		if ($res !== false) {
			if ($row = $res->fetch_assoc()) {
				$status = $row["status"];
			}
		}
		return $status;
	}
	
	public function insertOrUpdate($speeltuinId, $rating, $comment, $userId) {
		if ($this->id == 0) {
			$this->db->query(sprintf("INSERT INTO review (speeltuin_id, rating, comment, user_id, status, rated_on) 
					VALUES (%d, %d, \"%s\", %d, 0, NOW())", 
					$speeltuinId, $rating, $comment, $userId));
			$this->id = $this->db->getLatestId();
		} else {
			$previousStatus = $this->getStatus();
			// update
			$this->db->query(sprintf("UPDATE review SET speeltuin_id = %d, rating = %d, comment = \"%s\", 
					user_id = %d, status = 0, rated_on = NOW() WHERE id = %d", 
					$speeltuinId, $rating, $comment, $userId, $this->id));
			if ($previousStatus == 1) {
				// update speeltuin rating (-1)
				$this->db->query(sprintf("UPDATE speeltuin SET times_rated = times_rated - 1,
						total_rating = total_rating - %d WHERE id = %d", $rating, $speeltuinId));
				$this->db->query(sprintf("UPDATE speeltuin SET avg_rating = total_rating / times_rated WHERE id = %d", $speeltuinId));
			}
		}
		return $this->id;
	}
	
	public function activate() {
		$res = $this->db->query(sprintf("SELECT * FROM review WHERE id = %d", $this->id));
		if ($res !== false) {
			if ($row = $res->fetch_assoc()) {
				$rating = $row["rating"];
				$speeltuinId = $row["speeltuin_id"];
				$this->db->query(sprintf("UPDATE review SET status = 1 WHERE id = %d", $this->id));
				// update speeltuin rating (+1)
				$this->db->query(sprintf("UPDATE speeltuin SET times_rated = times_rated + 1,
					total_rating = total_rating + %d WHERE id = %d", $rating, $speeltuinId));
				$this->db->query(sprintf("UPDATE speeltuin SET avg_rating = total_rating / times_rated WHERE id = %d", $speeltuinId));
			}
		}	
	}
	
	public function deactivate() {
		$previousStatus = $this->getStatus();
		$this->db->query(sprintf("UPDATE review SET status = 2 WHERE id = %d", $this->id));
		if ($previousStatus == 1) {
			// update speeltuin rating (-1)
			$this->db->query(sprintf("UPDATE speeltuin SET times_rated = times_rated - 1,
					total_rating = total_rating - %d WHERE id = %d", $rating, $speeltuinId));
			$this->db->query(sprintf("UPDATE speeltuin SET avg_rating = total_rating / times_rated WHERE id = %d", $speeltuinId));
		}
	}
	
	public function delete() {
		$res = $this->db->query(sprintf("SELECT * FROM review WHERE id = %d", $this->id));
		if ($res !== false) {
			if ($row = $res->fetch_assoc()) {
				$rating = $row["rating"];
				$speeltuinId = $row["speeltuin_id"];
				$this->db->query(sprintf("DELETE FROM review WHERE id = %d", $this->id));
				if ($row["status"] == 1) {
					// update speeltuin rating (-1)
					$this->db->query(sprintf("UPDATE speeltuin SET times_rated = times_rated - 1,
						total_rating = total_rating - %d WHERE id = %d", $rating, $speeltuinId));
					$this->db->query(sprintf("UPDATE speeltuin SET avg_rating = total_rating / times_rated WHERE id = %d", $speeltuinId));
				}
			}
		}
	}
	
	public function getAuthor() {
		$res = $this->db->query(sprintf("SELECT user_id FROM review WHERE id = %d", $this->id));
		if ($res !== false) {
			if ($row = $res->fetch_assoc()) {
				return $row["user_id"];
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
	
	public function getSpeeltuin() {
		$res = $this->db->query(sprintf("SELECT speeltuin_id FROM review WHERE id = %d", $this->id));
		if ($res !== false) {
			if ($row = $res->fetch_assoc()) {
				return $row["speeltuin_id"];
			}
		}
		return 0;
	}
	
	public function getSpeeltuinName() {
		$speeltuinId = $this->getSpeeltuin();
		$res = $this->db->query(sprintf("SELECT naam FROM speeltuin WHERE id = %d", $speeltuinId));
		if ($res !== false) {
			if ($row = $res->fetch_assoc()) {
				return $row["naam"];
			}
		}
		return "Onbekend";
	}
	
	public function getSpeeltuinSeoUrl() {
		$speeltuinId = $this->getSpeeltuin();
		$res = $this->db->query(sprintf("SELECT seo_url FROM speeltuin WHERE id = %d", $speeltuinId));
		if ($res !== false) {
			if ($row = $res->fetch_assoc()) {
				return $row["seo_url"];
			}
		}
		return "";
	}
	
	public function getFields() {
		$res = $this->db->query(sprintf("SELECT * FROM review WHERE id = %d", $this->id));
		if ($res !== false) {
			if ($row = $res->fetch_assoc()) {
				return array($row["speeltuin_id"], $row["rating"], $row["comment"], $row["status"]);
			}
		}
		return array(0, 0, "", 0);
	}
}