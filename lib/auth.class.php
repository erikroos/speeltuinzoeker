<?php
class Auth {
	private $db;
	
	function __construct(Db $db = null) {
		$this->db = $db;
	}
	
	public function login($user_unesc, $pass_unesc) {
		$login = $this->db->realEscapeString($user_unesc);
		$password = $this->hashPassword($pass_unesc);
		
		$res = $this->db->query(sprintf("SELECT * FROM user WHERE active = 1 AND email = \"%s\" AND password=\"%s\"", $login, $password));
		if ($row = $res->fetch_assoc()) {
			$_SESSION["user_id"] = $row["id"];
			$_SESSION["user_name"] = $row["naam"];
			$_SESSION["admin"] = $row["admin"];
			return true;
		}
		
		return false;
	}
	
	public function userExists($email) {
		$res = $this->db->query(sprintf("SELECT * FROM user WHERE email = \"%s\"", $email));
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
			$message = "<p>Beste " . $name . ",</p>" . 
				"<p>Je hebt je aangemeld voor een account bij Speeltuinzoeker.nl. Welkom!<br>" . 
				"Je hoeft alleen nog even op " . 
				"<a href='" . BASE_URL . "admin/activate.php?code=" . $activationCode . "'>deze link</a>" . 
				" te klikken om je account te activeren." . 
				"Daarna kun je direct inloggen en beginnen met het invoeren en beoordelen van speeltuinen!</p>" . 
				"<p>Met vriendelijke groeten,<br>" . 
				"Het team van Speeltuinzoeker.nl</p>" . 
				"<p>PS: werkt de link niet? Voer dan dit webadres in in de adresbalk van je browser:<br>" . BASE_URL . "admin/activate.php?code=" . $activationCode . "</p>";
			Mail::sendMail($email, "Activeer je aanmelding bij Speeltuinzoeker.nl", $message);
		}
		
		return $res;
	}
	
	public function activateAccount($code) {
		$res = $this->db->query(sprintf("SELECT id, name, email FROM user WHERE active = 0 AND activation_code = \"%s\"", $this->db->realEscapeString($code)));
		if ($row = $res->fetch_assoc()) {
			$this->db->query(sprintf("UPDATE user SET active = 1, activation_code = \"\" WHERE id = %d", $row["id"]));
			
			$message = "<p>Gebruiker " . $name . " (e-mail: " . $email . ") heeft zijn/haar account geactiveerd.</p>";
			Mail::sendMail(ADMIN_MAIL, "Gebruiker " . $name . " geactiveerd", $message);
			
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
}