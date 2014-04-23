<?php

include_once 'lib/tools/url.php';

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
		$link = mysql_connect($this->server, $this->user, $this->password, false);
		return ($link && mysql_select_db($this->schema, $link));
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
	
	public static function fetchRow($result) {
		return mysql_fetch_array($result);
	}
	
	public static function fetchAllRows($result) {
		$a = array();
		$rowCount = DatabaseConnection::countRows($result);
		for ($i = 0; $i < $rowCount; $i++) {
			$a[$i] = DatabaseConnection::fetchRow($result);
		}
	}
}

function db_get_link() {
	$dbServer   = 'localhost';
	$dbSchema   = 'projekt';	
	$dbUser     = 'projekt';
	$dbPassword = 'projekt';
			
	$link = mysql_connect($dbServer, $dbUser, $dbPassword, false) or die("Keine Verbindung zur Datenbank möglich!");
	mysql_select_db($dbSchema, $link);
	return $link;
}

function db_exec_login() {
	if (filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_DEFAULT) === 'POST') {
		if (filter_has_var(INPUT_POST, 'username') && filter_has_var(INPUT_POST, 'password')) {
			$username = filter_input(INPUT_POST, 'username');
			$password = filter_input(INPUT_POST, 'password');
			
			$link = db_get_link();
			
			$query = 
				sprintf("SELECT id, username, password FROM users WHERE username = '%s' AND password = '%s';" ,
				mysql_real_escape_string($username),
				mysql_real_escape_string($password));
			$result = mysql_query($query, $link);
			
			if (!$result) {
				die('Fehler bei SQL Abfrage: '.mysql_error());
			}
			
			if (mysql_num_rows($result) == 1) {
			
				$row = mysql_fetch_array($result);
			
				// Benutzer als angemeldet speichern
				Session::setLoggedIn(true);
				Session::setUserName($username);
				Session::setUserID($row['id']);
				Session::setLoginFailed(false);

				// redirect to start page
				url_redirect(url_set_query_parameter(url_full(), 'action', NULL));
			} else {
				Session::setLoginFailed(true);
				
				// redirect to login page
				url_redirect(url_set_query_parameter(url_full(), 'action', 'login'));
			}
		}
	}
}

function db_exec_logout() {
	session_destroy();
	url_redirect(url_strip_query_parameters(url_relative('index.php')));
}

function db_add_calendar($user_id, $name){
	$link = db_get_link();
	$query = sprintf("SELECT id FROM calendars WHERE owner_id = '%s' AND name = '%s';",
		mysql_real_escape_string($user_id),
		mysql_real_escape_string($name));
		
	$result = mysql_query($query);
	if(mysql_num_rows($result) != 1){
		$query = sprintf("INSERT INTO calendars (name, owner_id) VALUES ('%s', '%s')",
			mysql_real_escape_string($name),
			mysql_real_escape_string($user_id));
		$result = mysql_query($query);
		return $result;
	} else {
		return false;
	}
}	
?>