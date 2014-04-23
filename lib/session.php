<?php

require_once 'lib/db.php';

class Session {

	private static function set($key, $value) {
		$_SESSION[$key] = $value;
	}
	
	private static function get($key) {
		if(isset($_SESSION[$key])){
			return  $_SESSION[$key];
		} else {
			return NULL;
		}
	}
	
	public static function setLoggedIn($loggedIn) {
		Session::set('logged_in', $loggedIn);
	}

	public static function isLoggedIn() {
		return Session::get('logged_in');
	}
	
	public static function setLoginFailed($loginFailed) {
		Session::set('login_failed', $loginFailed);
	}

	public static function loginFailed() {
		return Session::get('login_failed');
	}
	
	public static function setUserID($userID){
		Session::set('user_id', $userID);
	}
	
	public static function getUserID(){
		return Session::get('user_id');
	}
	
	public static function setUserName($userName){
		Session::set('user_name', $userName);
	}
	
	public static function getUserName(){
		return Session::get('user_name');
	}
	
	/**
	 * 
	 * @param DatabaseConnection $dbConnection
	 * @param string $username
	 * @param string $password
	 */
	public static function login(DatabaseConnection $dbConnection, $username, $password) {
		$result = $dbConnection->query(
			"SELECT id, username, password FROM users WHERE username='%s' AND password = '%s'",
			$username,
			$password);
		if(!$result) {
			die('Fehler bei SQL Abfrage: '.mysql_error());
		}
		if (DatabaseConnection::countRows($result) == 1) {
			$row = DatabaseConnection::fetchRow($result);
			Session::setLoggedIn(true);
			Session::setUserName($username);
			Session::setUserID($row['id']);
			Session::setLoginFailed(false);
			$url = URL::urlFromCurrent();
			$url->setQueryParameter('action', NULL);
			$url->redirect();
		} else {
			Session::setLoginFailed(true);
			$url = URL::urlFromCurrent();
			$url->setQueryParameter('action', 'login');
			$url->redirect();	
		}
		
	}
	
        public static function logout(){
            session_destroy();
            $url = URL::urlFromRelativePath('index.php');
            $url->redirect();
        }
}
