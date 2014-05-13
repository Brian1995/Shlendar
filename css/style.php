<?php

header("Content-type: text/css");

const BACKGROUND_SHARP  = "background:url('../img/background.jpg')        fixed; background-size: cover;";
//const BACKGROUND_SHARP  = "background: #68a; background-size: cover;";
const BACKGROUND_BLURED = "background:url('../img/background-blured.jpg') fixed; background-size: cover;";
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
	overflow: auto;
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

</style>