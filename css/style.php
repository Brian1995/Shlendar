<?php

header("Content-type: text/css");

const BACKGROUND_SHARP  = "url('../img/background3.jpg')        fixed; background-size: cover";
const BACKGROUND_BLURED = "url('../img/background-blured3.jpg') fixed; background-size: cover";
const BACKGROUND_HOVER  = "rgba(255,255,255,0.2)";
const BORDER_CONTAINER  = "1px solid rgba(255,255,255,0.2)";
const BORDER_HOVER      = "1px solid rgba(255,255,255,0.6)";
const BORDER_HOVER_NOT  = "1px solid transparent";
const FONT_HEADLINE     = "font-family: 'Ubuntu', sans-serif; font-weight:300;";
const FONT_PARAGRAPH    = "font-family: 'Ubuntu', sans-serif; font-weight:400;";

?>
<style>
	
*{border:0px;padding:0px;margin:0px;font-size:100%;font-weight:normal;text-decoration:none;}
*{border:0px;padding:0px;margin:0px;font-size:100%;font-weight:normal;text-decoration:none;}

h1, h2, h3, h4, h5, h6 { <?=FONT_HEADLINE?> }

html {
	height: 100%;
	<?=FONT_PARAGRAPH?>
}

body {
	display:flex;
	flex-flow: column nowrap;
	min-height: 100%;
	background: <?=BACKGROUND_SHARP?>;
}

header {
	display: flex;
	flex-flow: row nowrap;
	justify-content: space-between;
	align-items: stretch;
	background: <?=BACKGROUND_BLURED?>;
	border-bottom: <?=BORDER_CONTAINER?>;
	-webkit-box-shadow: 0 -3px 3px 2px rgba(0,0,0,1);
	box-shadow: 0 -3px 3px 2px rgba(0,0,0,1);

}

#content {
	flex: 1;
}

footer {
	height:80px;
	background: <?=BACKGROUND_BLURED?>;
	border-top: <?=BORDER_CONTAINER?>;
	-webkit-box-shadow: 0 3px 3px 2px rgba(0,0,0,1);
	box-shadow: 0 3px 3px 2px rgba(0,0,0,1);
}

/* header */
header .logo  { order: 1; display: flex; align-items: center; border-right: 1px solid transparent; }
header .title { order: 2; display: flex; align-items: center; }
header .login { order: 3; }

header .logo a { display: block; height: 64px; width:64px; background: url('../img/logo.png'); }
header .logo p { <?=FONT_HEADLINE?> font-size: 200%; color:#fff; text-shadow: 0 0 2px rgba(0,0,0,1); padding-right: 0.5em; }
header .logo:hover { background: <?=BACKGROUND_HOVER?>; border-right: <?=BORDER_CONTAINER?>; }
header .title h1 { text-align: center; width:100%; color: #fff; font-size: 140%; font-style: italic; text-shadow: 0 0 2px rgba(0,0,0,1); }
header .login a { display: flex; height: 100%; align-items: center; padding: 0 1em; color: #fff; text-shadow: 0 0 2px rgba(0,0,0,1); border-left: 1px solid transparent; }
header .login a:hover { background: <?=BACKGROUND_HOVER?>; border-left: <?=BORDER_CONTAINER?>; }

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
	background: <?=BACKGROUND_BLURED?>;
	border: <?=BORDER_CONTAINER?>;
	-webkit-box-shadow: 0 0 3px -1px rgba(0,0,0,1);
	box-shadow: 0 0 3px -1px rgba(0,0,0,1);
}

/* sidebar */
.sidebar-actions h1 { display: none; }
.sidebar-container { padding: 0.5em; }

#sidebar-calendar { font-size: 85%; border: 1px solid rgba(255,255,255,0.2);}
#sidebar-calendar h2 { display: none; }

#sidebar-calendar-header { display: flex; background: rgba(0,0,0,0.1); }
#sidebar-calendar-header .previous { display: flex; align-items: center; justify-content: center; }
#sidebar-calendar-header .title    { display: flex; align-items: center; justify-content: center; flex: 1; font-size:110%;}
#sidebar-calendar-header .next     { display: flex; align-items: center; justify-content: center; }

#sidebar-calendar-header .previous a { 
	display: flex; align-items: center; justify-content: center; 
	padding: 0.5em 0.2em; color: #fff; opacity: 0.6;
	border: <?=BORDER_HOVER_NOT?>;
}
#sidebar-calendar-header .previous a:hover { opacity: 1.0; border: <?=BORDER_HOVER?>; background: <?=BACKGROUND_HOVER?>; }

#sidebar-calendar-header .title h3 { 
	color: #fff; padding: 0.4em; 
}
#sidebar-calendar-header .next a { 
	display: flex; align-items: center; justify-content: center; 
	padding: 0.5em 0.2em; color: #fff; opacity: 0.6;
	border: <?=BORDER_HOVER_NOT?>;
}
#sidebar-calendar-header .next a:hover { opacity: 1.0; border: <?=BORDER_HOVER?>; background: <?=BACKGROUND_HOVER?>; }

#sidebar-calendar-entries { display: table; }
#sidebar-calendar-entries > div { display: table-row; }
#sidebar-calendar-entries .day { display: table-cell; padding: 0.2em; color: #fff; background: rgba(0,0,0,0.2); }
#sidebar-calendar-entries .cell { display: table-cell;  color: #fff; text-align: center; background: rgba(0,0,0,0.1); }
#sidebar-calendar-entries .cell a { display: block; padding: 0.35em; color: rgba(255,255,255,0.6); border: <?=BORDER_HOVER_NOT?>; }
#sidebar-calendar-entries .cell.current-month a { color: rgba(255,255,255,1.0); }
#sidebar-calendar-entries .cell.weekend { background: rgba(0,0,0,0.2); }
#sidebar-calendar-entries .cell a:hover { border: <?=BORDER_HOVER?>; background: <?=BACKGROUND_HOVER?>; }



</style>