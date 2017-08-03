<?php
class Db {
	private $dbHost = null;
	private $dbUser = null;
	private $dbPass = null;
	private $dbName = null;
	private $link = null;
	
	public function connect() {
		if ($this->link == null) {
			if (gethostname() == 'Eriks_DellXPS') {
				$this->dbHost = 'localhost';
				$this->dbUser = 'root';
				$this->dbPass = '';
				$this->dbName = 'speeltuinzoeker';
			} else {
				// TODO!
				$this->dbHost = 'localhost';
				$this->dbUser = '';
				$this->dbPass = '';
				$this->dbName = 'speeltuinzoeker';
			}
			$this->link = mysqli_connect($this->dbHost, $this->dbUser, $this->dbPass, $this->dbName);
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