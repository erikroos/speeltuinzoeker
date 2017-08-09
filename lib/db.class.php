<?php
class Db {
	private $link = null;
	
	public function connect() {
		if ($this->link == null) {
			$this->link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		}
		if (!$this->link) {
			echo "Error: Unable to connect to MySQL.<br>";
			echo "Debugging errno: " . mysqli_connect_errno() . "<br>";
			echo "Debugging error: " . mysqli_connect_error() . "<br>";
			exit;
		}
		return $this->link;
	}
	
	public function query($query) {
		if ($this->link == null) {
			$this->connect();
		}
		return mysqli_query($this->link, $query);
	}
	
	public function getLatestId() {
		return mysqli_insert_id($this->link);
	}
	
	public function close() {
		if ($this->link == null) {
			return true;
		}
		return mysqli_close($this->link);
	}
	
	public function realEscapeString($string) {
		return mysqli_real_escape_string($this->link, $string);
	}
}