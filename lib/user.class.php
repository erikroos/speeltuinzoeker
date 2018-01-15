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
	
	public function getAllUsers($active = 1, $start = 0, $size = 10, $q = "") {
		$allUsers = [];
		
		$whereClause = "";
		$limitClause = "LIMIT " . $start . "," . $size;
		if (!empty($q)) {
			$whereClause = " AND (user.naam LIKE \"%" . $q . "%\" OR user.email LIKE \"%" . $q . "%\")";
			$limitClause = "";
		}
		
		$res = $this->db->query(sprintf("SELECT * FROM user WHERE active = %d%s %s", $active, $whereClause, $limitClause));
		while ($row = $res->fetch_assoc()) {
			$allUsers[] = $row;
		}
		return $allUsers;
	}
	
	public function getTotalNr($active = 1, $q = "") {
		
		$whereClause = "";
		if (!empty($q)) {
			$whereClause = " AND (user.naam LIKE \"%" . $q . "%\" OR user.email LIKE \"%" . $q . "%\")";
		}
		
		$res = $this->db->query(sprintf("SELECT COUNT(*) AS totNr FROM user WHERE active = %d%s", $active, $whereClause));
		if ($res !== false) {
			if ($row = $res->fetch_assoc()) {
				return $row["totNr"];
			}
		}
		return 0;
	}
	
	public function getByEmail($email) {
		$res = $this->db->query(sprintf("SELECT id FROM user WHERE email = \"%s\"", $email));
		if ($res !== false) {
			if ($row = $res->fetch_assoc()) {
				$this->id = $row["id"];
				return true;
			}
		}
		return false;
	}
	
	// Instance functions:
	
	public function getId() {
		return $this->id;
	}
	
	public function getName() {
		$res = $this->db->query(sprintf("SELECT naam FROM user WHERE id = %d", $this->id));
		if ($res !== false) {
			if ($row = $res->fetch_assoc()) {
				return $row["naam"];
			}
		}
		return "Onbekend";
	}
	
	public function getEmail() {
		$res = $this->db->query(sprintf("SELECT email FROM user WHERE id = %d", $this->id));
		if ($res !== false) {
			if ($row = $res->fetch_assoc()) {
				return $row["email"];
			}
		}
		return null;
	}
	
	public function getNrOfLogins() {
		$res = $this->db->query(sprintf("SELECT nr_of_logins FROM user WHERE id = %d", $this->id));
		if ($res !== false) {
			if ($row = $res->fetch_assoc()) {
				return $row["nr_of_logins"];
			}
		}
		return "Onbekend";
	}
	
	public function getLastLogin() {
		$res = $this->db->query(sprintf("SELECT last_login FROM user WHERE id = %d", $this->id));
		if ($res !== false) {
			if ($row = $res->fetch_assoc()) {
				return $row["last_login"];
			}
		}
		return "Onbekend";
	}
	
	public function setName($name) {
		$this->db->query(sprintf("UPDATE user SET naam = \"%s\" WHERE id = %d", $name, $this->id));
	}
	
	public function setPassword(Auth $auth, $password) {
		$password = $auth->hashPassword($password);
		$this->db->query(sprintf("UPDATE user SET password = \"%s\" WHERE id = %d", $password, $this->id));
	}
	
	public function setPasswordGenerated($value) {
		$this->db->query(sprintf("UPDATE user SET password_generated = %d WHERE id = %d", $value, $this->id));
	}
	
	public function deactivate() {
		$this->db->query(sprintf("UPDATE user SET active = 0 WHERE id = %d", $this->id));
	}
	
	public function delete() {
		
		$defaultUser = new User($this->db);
		$defaultUser->getByEmail("tom.erik.roos@gmail.com");
		
		// Zet speeltuinen over naar default-user
		$this->db->query(sprintf("UPDATE speeltuin SET author_id = %d WHERE author_id = %d", $defaultUser->getId(), $this->id));
		
		// Reviews ook over naar default-user (TODO is dit wenselijk?)
		$this->db->query(sprintf("UPDATE review SET user_id = %d WHERE user_id = %d", $defaultUser->getId(), $this->id));
		
		$this->db->query(sprintf("DELETE FROM user WHERE id = %d", $this->id));
	}
}