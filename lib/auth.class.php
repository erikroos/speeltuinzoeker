<?php
class Auth {
	private $db;
	
	function __construct(Db $db = null) {
		$this->db = $db;
	}
	
	public function login($user_unesc, $pass_unesc) {
		$login = strtolower($this->db->realEscapeString($user_unesc));
		$password = $this->hashPassword($pass_unesc);
		
		$res = $this->db->query(sprintf("SELECT * FROM `user` WHERE active = 1 AND LOWER(email) = \"%s\" AND `password`=\"%s\"", $login, $password));
		if ($row = $res->fetch_assoc()) {
			$_SESSION["user_id"] = $row["id"];
			$_SESSION["user_name"] = $row["naam"];
			$_SESSION["admin"] = $row["admin"];
			$_SESSION["password_generated"] = $row["password_generated"];
			
			$this->db->query(sprintf("UPDATE `user` SET last_login = NOW(), nr_of_logins = nr_of_logins + 1 WHERE id = %d", $row["id"]));
			
			return true;
		}
		
		return false;
	}
	
	public function userExists($email) {
		$res = $this->db->query(sprintf("SELECT * FROM `user` WHERE email = \"%s\"", $email));
		if ($row = $res->fetch_assoc()) {
			return true;
		}
		return false;
	}
	
	public function createNewAccount($name, $email, $password) {
		$activationCode = uniqid();
		
		$res = $this->db->query(sprintf("INSERT INTO user 
				(naam, email, password, admin, active, activation_code) 
				VALUES (\"%s\", \"%s\", \"%s\", 0, 0, \"%s\");", $this->db->realEscapeString($name), $this->db->realEscapeString($email), $this->hashPassword($password), $activationCode));
		
		if ($res == true) {
			Mail::sendActivationInstructions($name, $activationCode, $email);
		}
		
		return $res;
	}
	
	public function activateAccount($code) {
		$res = $this->db->query(sprintf("SELECT id, naam, email FROM user WHERE active = 0 AND activation_code = \"%s\"", $this->db->realEscapeString($code)));
		if ($row = $res->fetch_assoc()) {
			$this->db->query(sprintf("UPDATE user SET active = 1, activation_code = \"\" WHERE id = %d", $row["id"]));
			
			Mail::sendUserActivatedToAdmin($row["naam"], $row["email"]);
			
			return true;
		}
		return false;
	}
	
	public function logout() {
		session_unset();
	}
	
	public function hashPassword($password) {
		return md5($password . SALT);
	}
	
	public function randomPassword() {
	    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
	    $pass = [];
	    $alphaLength = strlen($alphabet) - 1;
	    for ($i = 0; $i < 8; $i++) {
	        $n = rand(0, $alphaLength);
	        $pass[] = $alphabet[$n];
	    }
	    return implode($pass);
	}
	
	public function getLoggedInUser() {
		return new User($this->db, $_SESSION["user_id"]);
	}
}