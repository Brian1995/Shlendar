<?php

header("Content-type: text/css");

const BACKGROUND_SHARP  = "background:url('../img/background.jpg')        fixed; background-size: cover;";
const BACKGROUND_BLURED = "background:url('../img/background-blured.jpg') fixed; background-size: cover;";

?>
<style>
	
*{border:0px;padding:0px;margin:0px;font-size:100%;font-weight:normal;text-decoration:none;}
*{border:0px;padding:0px;margin:0px;font-size:100%;font-weight:normal;text-decoration:none;}

html, body {
	height: 100%;
	font-family: 'Open Sans', sans-serif;
}

body {
	<?=BACKGROUND_SHARP?>
}

#page {
	display: table;
	min-height: 100%;
	height: 100%;
	width: 100%;
}

header {
	display: table-row;
	width: 100%;
}

#page-content {
    display:table-row;
	height:100%;
    width:100%;
}

/** Header */
#site-header {
	display: table;
	height: 100%;
	<?=BACKGROUND_BLURED?>
}

#site-header > div {
	display: table-row;
}

#site-header > div > div {
	vertical-align: middle;
}

#site-header > div > .logo {
	display: table-cell;
	width: 0%;
}

#site-header > div > .title {
	display: table-cell;
	width:100%;
}

#site-header > div > .login {
	display: table-cell;
	width: 0%;
	height: 100%;
}

#site-header > div > .logo > a {
	display: block;
}

#site-header > div > .logo > a > img {
	vertical-align: bottom;
}

#site-header .title h1 {
	color: #fff;
	font-size: 150%;
	letter-spacing: 0.05em;
	text-shadow: 0px 0px 2px rgba(0, 0, 0, 0.6);
	text-align: center;
}

#site-header > div > .login > a {
    display: flex;
    justify-content: center; /* align horizontal */
    align-items: center; /* align vertical */
	height: 100%;
	padding: 0 2em;
	color: #fff;
	background: rgba(100,200,255,0.1);
	letter-spacing: 0.05em;
	border-left: 1px solid rgba(255,255,255,0.5);
}

#site-header > div > .login > a:hover {
	background: rgba(100,200,255,0.2);
}

#page-content > div  {
	display: table;
	height:100%;
}

#page-content > div > div {
	display: table-row;
}
#page-content > div > div > div {
	display: table-cell;
}

#page-content > div > div > .left {
	width: 0%;
	vertical-align: top;
	<?=BACKGROUND_BLURED?>
}

#page-content > div > div > .center {
	width: 100%;
	border: 1px solid rgba(255,255,255,0.5);
	border-bottom:0;
	border-right:0;
	-webkit-box-shadow:inset 2px 2px 3px -2px rgba(0,0,0,0.5);
	box-shadow:inset 2px 2px 3px -2px rgba(0,0,0,0.5);
	vertical-align: top;
}

#page-content > div > div > .right {
	width: 0%;
	vertical-align: top;
}

#sidebar-actions {
}

#sidebar-actions > h1 {
	display: none;
}

.sidebar-container {
	padding: 0.8em;
}

#sidebar-calendar > h2 {
	display: none;
}

#sidebar-calendar-header {
	display: table;
	width: 100%;
}

#sidebar-calendar-header > div {
	display: table-row;
}

#sidebar-calendar-header > div > div {
	display: table-cell;
}

#sidebar-calendar-header .title {
	white-space: nowrap;
	color: #fff;
	padding: 0 0.5em;
	text-align: center;
}

#sidebar-calendar-header .previous a {
	display: block;
	color: #fff;
	padding: 0 0.3em;
}

#sidebar-calendar-header .next a {
	display: block;
	color: #fff;
	padding: 0 0.3em;
}

#sidebar-calendar-entries {
	display: table;
	font-size: 90%;
	text-align: center;
}

#sidebar-calendar-entries > div {
	display: table-row;
}

#sidebar-calendar-entries > div > div {
	display: table-cell;
}

#sidebar-calendar-entries > div > div > a {
	display: block;
	padding: 0 0.3em;
	color: rgba(255,255,255,0.5);
}

#sidebar-calendar-entries > div > div.current-month > a {
	color: rgba(255,255,255,0.8);
}

</style>