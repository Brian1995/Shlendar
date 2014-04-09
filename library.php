<?php

include_once 'lib/calendar.php';
include_once 'lib/db.php';
include_once 'lib/pageelements.php';
include_once 'lib/render.php';
require_once 'lib/xml.php';

mb_internal_encoding("UTF-8");
setlocale(LC_ALL, 'de_DE.utf-8');
session_start();

function beginPage ($title="kein Titel") {
	/**echo '<?xml version="1.0" encoding="UTF-8"?>'."\r\n";**/
?><html><head><title>xxx</title></head><body><?php
}

function endPage() {
?>
	</body>
</html>
<?php
}
	
	function getLocation($filepath) {
		return $_SERVER['HTTP_HOST'].dirname($_SERVER['REQUEST_URI']).'/'.$filepath;
	}
	
 
	
	function drawNextAppointments(){
		$link = getDatabaseLink();
	}