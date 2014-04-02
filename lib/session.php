<?php

/**
 * Description of session
 *
 * @author bussebr
 */
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
		set('logged_in', $loggedIn);
	}

	public static function isLoggedIn() {
		return get('logged_in');
	}
	
	public static function setLoginFailed($loginFailed) {
		set('login_failed', $loginFailed);
	}

	public static function loginFailed() {
		return get('login_failed');
	}
	
	public static function setUserID($userID){
		set('user_id', $userID);
	}
	
	public static function getUserID(){
		return get('user_id');
	}
	
	public static function setUserName($userName){
		set('user_name', $userName);
	}
	
	public static function getUserName(){
		return get('user_name');
	}
}
