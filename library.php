<?php

include_once 'lib/calendar.php';
include_once 'lib/db.php';
include_once 'lib/elements.php';
include_once 'lib/render.php';

setlocale(LC_ALL, 'de_DE.utf-8');
session_start();

function beginPage ($title="kein Titel") { 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html class="table">
	<head>
		<title><?= $title ?></title>
		<link rel="stylesheet" type="text/css" href="css/style.css" media="screen" />
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	</head>
	<body class="row sharp">
		<div class="cell vcenter">
			<div id="page" class="table blured round">
				<div class="row round">
					<div class="cell round">
<?php
}

function endPage() {
?>
					</div>
				</div>
			</div>
		</div>
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