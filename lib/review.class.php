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
	
	
	
	// Instance functions:
	
	public function insertOrUpdate($speeltuinId, $rating, $comment, $userId) {
		if ($this->id == 0) {
			$this->db->query(sprintf("INSERT INTO review (speeltuin_id, rating, comment, user_id) 
					VALUES (%d, %d, \"%s\", %d)", $speeltuinId, $rating, $comment, $userId));
			$this->id = $this->db->getLatestId();
			
			$this->db->query(sprintf("UPDATE speeltuin SET times_rated = times_rated + 1, 
					total_rating = total_rating + %d WHERE id = %d", $rating, $speeltuinId));
			
			$this->db->query(sprintf("UPDATE speeltuin SET avg_rating = total_rating / times_rated WHERE id = %d", $speeltuinId));
			
		} else {
			$this->db->query(sprintf("UPDATE "));
		}
		return $this->id;
	}
	
	public function delete() {
		$this->db->query(sprintf("DELETE FROM review WHERE id = %d", $this->id));
	}
}