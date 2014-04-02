<?php

/**
 * Description of session
 *
 * @author bussebr
 */
class Session {
    
    public static function setLoggedIn($loggedIn){
        $_SESSION['logged_in'] = $loggedIn;
    }

    public static function isLoggedIn() {
	return isset($_SESSION['logged_in']) && $_SESSION['logged_in'];
    }
    
}
