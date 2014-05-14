<?php

header("Content-type: text/css");

const BACKGROUND_SHARP  = "background:url('../img/background4.jpg')        fixed; background-size: cover;";
//const BACKGROUND_SHARP  = "background: #68a; background-size: cover;";
const BACKGROUND_BLURED = "background:url('../img/background-blured4.jpg') fixed; background-size: cover;";
//const BACKGROUND_BLURED = "background: #8bd; background-size: cover;";

?>
<style>
	
*{border:0px;padding:0px;margin:0px;font-size:100%;font-weight:normal;text-decoration:none;}
*{border:0px;padding:0px;margin:0px;font-size:100%;font-weight:normal;text-decoration:none;}

html {
	height: 100%;
	font-family: 'Open Sans', sans-serif;
}

body {
	display:flex;
	flex-flow: column nowrap;
	min-height: 100%;
	<?=BACKGROUND_SHARP?>
}

header {
	<?=BACKGROUND_BLURED?>
	display: flex;
	flex-flow: row nowrap;
	align-items: stretch;
	border-bottom: 1px solid rgba(255,255,255,0.3);
	-webkit-box-shadow: 0 -3px 3px 2px rgba(0,0,0,1);
	box-shadow: 0 -3px 3px 2px rgba(0,0,0,1);

}

#content {
	flex: 1;
}

footer {
	height:20px;
	background: orange;
}

/* header */
header .logo  {}
header .title {flex: 1; display: flex; align-items: center; }
header .login {}

header .logo a { display: block; height: 100%; }
header .logo img { vertical-align: bottom; }
header .title h1 { text-align: center; width:100%; color: #fff; font-weight:300; font-size: 200%; text-shadow: 0 0 2px rgba(0,0,0,1); }
header .login a { display: flex; height: 100%; align-items: center; padding: 0 1em; color: #fff; text-shadow: 0 0 2px rgba(0,0,0,1); border-left: 1px solid transparent; }
header .login a:hover { background: rgba(255,255,255,0.2); border-left: 1px solid rgba(255,255,255,0.3); }

/* content */
#content > div {
	display: flex;
	justify-content: space-around;
	align-items: flex-start;
	box-sizing: border-box;
	height:100%;
	width:100%;
	padding: 2em 0;
}

#content > div > .left {
	<?=BACKGROUND_BLURED?>
	border: 1px solid rgba(255,255,255,0.3);
	-webkit-box-shadow: 0 0 3px -1px rgba(0,0,0,1);
	box-shadow: 0 0 3px -1px rgba(0,0,0,1);
}

/* sidebar */
.sidebar-actions h1 { display: none; }
.sidebar-container { padding: 0.5em; }

#sidebar-calendar { font-size: 90%; }
#sidebar-calendar h2 { display: none; }

#sidebar-calendar-header { display: flex; }
#sidebar-calendar-header .previous { display: flex; align-items: center; justify-content: center; }
#sidebar-calendar-header .title    { display: flex; align-items: center; justify-content: center; flex: 1; }
#sidebar-calendar-header .next     { display: flex; align-items: center; justify-content: center; }

#sidebar-calendar-header .previous a { 
	display: flex; align-items: center; justify-content: center; 
	height: 24px; width: 24px; padding: 0.2em; box-sizing: border-box;
	color: #fff;
	background: url('../img/previous24.png'); opacity: 0.6;
}
#sidebar-calendar-header .previous a:hover { opacity: 1.0; }
#sidebar-calendar-header .previous a span { display: none; }

#sidebar-calendar-header .title h3 { 
	color: #fff; padding: 0.4em; 
}
#sidebar-calendar-header .next a { 
	display: flex; align-items: center; justify-content: center; 
	height: 24px; width: 24px; padding: 0.2em; box-sizing: border-box; 
	color: #fff;
	background: url('../img/next24.png'); opacity: 0.6;
}
#sidebar-calendar-header .next a:hover { opacity: 1.0; }
#sidebar-calendar-header .next a span { display: none; }

#sidebar-calendar-entries { display: table; }
#sidebar-calendar-entries > div { display: table-row; }
#sidebar-calendar-entries .day { display: table-cell; padding: 0.2em; color: #fff; }
#sidebar-calendar-entries .cell { display: table-cell;  color: #fff; text-align: center; }
#sidebar-calendar-entries .cell a { display: block; padding: 0.2em 0.4em; color: rgba(255,255,255,0.6); border: 1px solid transparent; }
#sidebar-calendar-entries .cell.current-month a { color: rgba(255,255,255,1.0); }
#sidebar-calendar-entries .cell a:hover { border: 1px solid rgba(255,255,255,0.6); background: rgba(0,0,0,0.1); }



</style>