<?php

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
	
}
