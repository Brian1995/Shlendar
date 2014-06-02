<?php

class DatabaseConnection {
	
	private $server;
	private $schema;
	private $user;
	private $password;
	private $link;
	
	public function __construct(
			$server   = 'localhost',
			$schema   = 'projekt',
			$user     = 'projekt',
			$password = 'projekt') {
		$this->server   = $server;
		$this->schema   = $schema;
		$this->user     = $user;
		$this->password = $password;
		$this->link     = NULL;
	}
	
	public function connect() {
		$this->link = mysql_connect($this->server, $this->user, $this->password, false);
		return ($this->link && mysql_select_db($this->schema, $this->link));
	}
	
	public function query($query) {
		$a = array();
		$ac = func_num_args();
		$args = func_get_args();
		for ($i = 1; $i < $ac; $i++) {
			$a[] = mysql_real_escape_string($args[$i]);
		}
		$finalQuery = vsprintf($query, $a);
		return mysql_query($finalQuery, $this->link);
	}
	
	public static function countRows($result) {
		return mysql_num_rows($result);
	}
	
	/**
	 * Returns an mapped array of strings that contains the result of a single 
	 * result row or FALSE if no more rows are available.
	 * 
	 * @param resource $result
	 * @return boolean|array
	 */
	public static function fetchRow($result) {
		return mysql_fetch_array($result);
	}
	
	/**
	 * Returns an indexed array of mapped arrays of strings that contain the 
	 * entire result.
	 * 
	 * @param resource $result
	 * @return 
	 */
	public static function fetchAllRows($result) {
		$a = array();
		$rowCount = DatabaseConnection::countRows($result);
		for ($i = 0; $i < $rowCount; $i++) {
			$r = DatabaseConnection::fetchRow($result);
			if ($r === FALSE) {
				return FALSE;
			}
			$a[$i] = $r;
		}
		return $a;
	}
}
