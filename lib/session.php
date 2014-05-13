<?php

require_once 'lib/utils.php';

class Session {

	private static function set($key, $value) {
		$_SESSION[$key] = $value;
	}

	private static function get($key) {
		if (isset($_SESSION[$key])) {
			return $_SESSION[$key];
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

	/**
	 * 
	 * @param bool $loginFailed
	 */
	public static function setLoginFailed($loginFailed) {
		Session::set('login_failed', $loginFailed);
	}

	/**
	 * 
	 * @return bool
	 */
	public static function loginFailed() {
		return Session::get('login_failed');
	}

	public static function setUserID($userID) {
		Session::set('user_id', $userID);
	}

	public static function getUserID() {
		return Session::get('user_id');
	}

	public static function setUserName($userName) {
		Session::set('user_name', $userName);
	}

	
	/**
	 * 
	 * @param Date $viewDate
	 */
	public static function setViewDate($viewDate) {
		Session::set('view_date', $viewDate);
	}

	public static function execLogin(DatabaseConnection $dbConnection, $loginUrl=NULL, $failedUrl=NULL) {
		if (filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_DEFAULT) === 'POST') {
			if (filter_has_var(INPUT_POST, 'username') && filter_has_var(INPUT_POST, 'password')) {
				$username = filter_input(INPUT_POST, 'username');
				$password = filter_input(INPUT_POST, 'password');
				return Session::login($dbConnection, $username, $password, $loginUrl, $failedUrl);
			}
		}
		return false;
	}

	/**
	 * 
	 * @param DatabaseConnection $dbConnection
	 * @param string $username
	 * @param string $password
	 */
	public static function login(DatabaseConnection $dbConnection, $username, $password, $loginUrl, $failedUrl) {
		$result = $dbConnection->query(
				"SELECT id, username, password FROM users WHERE username='%s' AND password = '%s'", $username, $password);
		if (!$result) {
			die('Fehler bei SQL Abfrage: ' . mysql_error());
		}
		if (DatabaseConnection::countRows($result) == 1) {
			$row = DatabaseConnection::fetchRow($result);
			Session::setLoggedIn(true);
			Session::setUserName($username);
			Session::setUserID($row['id']);
			Session::setLoginFailed(false);
			if ($loginUrl === NULL) {
				$loginUrl = URL::urlFromCurrent();
				$loginUrl->setQueryParameter('action', NULL);
			}
			$loginUrl->redirect();
		} else {
			Session::setLoginFailed(true);
			if ($failedUrl === NULL) {
				$failedUrl = URL::urlFromCurrent();
				$failedUrl->setQueryParameter('action', 'login');
			}
			$failedUrl->redirect();
		}
	}

	/**
	 * 
	 * @param URL|null $logoutUrl
	 */
	public static function logout($logoutUrl=NULL) {
		session_destroy();
		if ($logoutUrl === NULL) {
			$logoutUrl = URL::urlFromRelativePath('index.php');
			$logoutUrl->setQuery(NULL);
		}
		$logoutUrl->redirect();
	}

}
