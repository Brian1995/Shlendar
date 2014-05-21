<?php

header("Content-type: text/css");

const BLUE_LIGHTER   = 'rgba( 52,152,219,1.0)';
const BLUE_LIGHT     = 'rgba( 41,128,185,1.0)';
const BLUE_DARK      = 'rgba( 52, 73, 94,1.0)';
const BLUE_DARKER    = 'rgba( 44, 62, 80,1.0)';
const GRAY_LIGHTER   = 'rgba(236,240,241,1.0)';
const GRAY_LIGHT     = 'rgba(189,195,199,1.0)';
const GRAY_DARK      = 'rgba(149,165,166,1.0)';
const GRAY_DARKER    = 'rgba(127,140,141,1.0)';
const GREEN          = 'rgba( 46,204,113,1.0)';
const GREEN_DARK     = 'rgba( 39,174, 96,1.0)';
const ORANGE         = 'rgba(230,126, 34,1.0)';
const ORANGE_DARK    = 'rgba(211, 84,  0,1.0)';
const PURPLE         = 'rgba(155, 89,182,1.0)';
const PURPLE_DARK    = 'rgba(142, 68,173,1.0)';
const TURQUOISE      = 'rgba( 26,188,156,1.0)';
const TURQUOISE_DARK = 'rgba( 22,160,133,1.0)';
const WHITE          = 'rgba(255,255,255,1.0)';
const YELLOW         = 'rgba(241,196, 15,1.0)';
const YELLOW_DARK    = 'rgba(243,156, 18,1.0)';
const RED            = 'rgba(231, 76, 60,1.0)';
const RED_DARK       = 'rgba(192, 57, 43,1.0)';
const CONTENT_TEXT   = 'rgba( 80, 80, 75,1.0)';

const FONT_HEADLINE  = "font-family: 'Open Sans', sans-serif; font-weight:300;";
const FONT_PARAGRAPH = "font-family: 'Open Sans', sans-serif; font-weight:300;";
const FONT_IMPORTANT = "font-family: 'Open Sans', sans-serif; font-weight:400;";

$C_BASE         = "background: ".WHITE."; color: ".GRAY_DARKER.";";
$C_HEADER       = "background: rgba( 44, 62, 80,1.0); color: rgba(255,255,255,1.0);";
$C_HEADER_HOVER = "background: rgba( 52, 73, 94,1.0); color: rgba(255,255,255,1.0);";
$C_FOOTER       = "background: rgba( 44, 62, 80,1.0); color: rgba(255,255,255,1.0);";
$C_SIDEBAR      = "background: rgba(189,195,199,1.0); color: rgba( 44, 62, 80,1.0);";
$C_CONTENT      = "background: transparent; color: ".CONTENT_TEXT.";";
$C_CONTENT_HEAD = "background: transparent; color: ".BLUE_DARKER.";";
$C_WARNING      = "background: ".RED."; color: ".WHITE.";";

?>
<style>
	
*{border:0px;padding:0px;margin:0px;font-size:100%;font-weight:inherit;text-decoration:inherit;color:inherit;background:transparent;}
*{border:0px;padding:0px;margin:0px;font-size:100%;font-weight:inherit;text-decoration:inherit;color:inherit;background:transparent;}

h1, h2, h3, h4, h5, h6 { <?=FONT_HEADLINE?> }

html { height: 100%; <?=FONT_PARAGRAPH?> }
body { min-height: 100%; width: 100%; <?=$C_BASE?> }

/** HEADER, CONTENT AND FOOTER ************************************************/
body   { display: flex; flex-flow: column nowrap; }
header { flex: 0 0 auto; <?=$C_HEADER?> }
main   { flex: 1 0 auto; }
footer { flex: 0 0 auto; <?=$C_FOOTER?> }

/** HEADER ********************************************************************/
header { display: flex; flex-flow: row nowrap; justify-content: space-between; }
header .logo           { display: flex; }
header .header-actions { display: flex; }

header .logo a { display: flex; align-items: center; }
header .logo a:hover { <?=$C_HEADER_HOVER?> }
header .logo .icon { width: 64px; height: 64px; background: url('../img/logo.png'); }
header .logo .text { <?=FONT_HEADLINE?> font-size: 2em; padding-right: 0.5em; }

header .header-actions a { display: flex; align-items: center; }
header .header-actions a:hover { <?=$C_HEADER_HOVER?> }
header .header-actions a span { display: block; <?=FONT_HEADLINE?> font-size:1.2em; padding: 0.5em; }

/** MAIN **********************************************************************/
main { display: flex; }
#main-columns { flex: 1 0 auto; display: flex; flex-flow: row wrap; align-content: flex-start; }
#sidebar { flex: 1 0 auto; }
#content { flex: 100000 1 240px; min-width: 240px; }

/** SIDEBAR *******************************************************************/
#sidebar { <?=$C_SIDEBAR?> }
#sidebar h2 { display: none; }

/** SIDEBAR CALENDAR **********************************************************/
#sidebar-calendar { padding: 0.5em; margin: 0 auto; margin-top: 0.5em; max-width:20em; }
#sidebar-calendar-header { display: table; padding: 0 0.5em; width:100%; box-sizing: border-box; }
#sidebar-calendar-header .previous { display: table-cell; width:49%; text-align: left; }
#sidebar-calendar-header .title    { display: table-cell; width:2%; white-space: nowrap; }
#sidebar-calendar-header .next     { display: table-cell; width:49%; text-align: right; }
#sidebar-calendar-header a { display: block; height: 100%; width: 100%; }
#sidebar-calendar-header .previous a:hover { background: <?=GRAY_LIGHTER?>; background: linear-gradient(to right, rgba(236,240,241,1) 0%,rgba(236,240,241,0) 100%); }
#sidebar-calendar-header .next     a:hover { background: <?=GRAY_LIGHTER?>; background: linear-gradient(to left, rgba(236,240,241,1) 0%,rgba(236,240,241,0) 100%); }
#sidebar-calendar-entries { display: table; width: 100%; box-sizing: border-box; text-align: center; padding: 0 0.5em; }
#sidebar-calendar-entries > div { display: table-row; }
#sidebar-calendar-entries > div > div { display: table-cell;  }
#sidebar-calendar-entries > div > div > a { display:block; font-size: 0.9em; padding: 0.2em 0.4em; color: <?=GRAY_DARKER?>; }
#sidebar-calendar-entries .current-month a { color: <?=BLUE_DARKER?>; }
#sidebar-calendar-entries .current-month.current a { color: <?=WHITE?>; background: <?=GRAY_DARKER?>;}
#sidebar-calendar-entries .current-month.selected a { color: <?=WHITE?>; background: <?=ORANGE?>; }
#sidebar-calendar-entries > div > div > a:hover { background: <?=GRAY_LIGHTER?>; }
#sidebar-calendar-entries .dayrow { text-transform: uppercase; <?=FONT_HEADLINE?> font-size:0.8em; }

/** SIDEBAR ACTIONS ***********************************************************/
#sidebar-actions { font-size: 0.9em; padding: 0.5em 0; }
#sidebar-actions .container { display: table; margin: 0 auto; }
#sidebar-actions .action { display: flex; }
#sidebar-actions .action a { display: block; width: 100%; height: 100%; padding: 0.5em; }
#sidebar-actions .action a:hover { background: <?=GRAY_LIGHTER?>; }

/** CONTENT *******************************************************************/
#content { <?=$C_CONTENT?> <?=FONT_PARAGRAPH?> }
#content > div { margin: 1em; }
#content > div > h1 { padding-bottom: 0.7em; font-size: 1.6em; <?=$C_CONTENT_HEAD?> <?=FONT_HEADLINE?> }
/** LOGIN *********************************************************************/

#login-failed { padding: 0.5em; margin-bottom: 0.7em; <?=$C_WARNING?> <?=FONT_IMPORTANT?> display: table; }
#login-failed-text1 { margin-bottom: 0.5em; font-style: italic; }
#login-failed-text2 { font-size: 0.8em; }
#login-form { display: inline-flex; flex-flow: column wrap; }
#login-user, #login-pass { flex: 1 0 auto; display: flex; flex-flow: row wrap; justify-content: flex-start; align-items: center; }
#login-user > div, #login-pass > div { flex: 1 0 5em; margin-left: 0; }
#login-user > input, #login-pass > input { flex: 1 0 auto; border: 1px solid <?=GRAY_LIGHT?>; background: <?=GRAY_LIGHTER?>; margin: 0.4em 0; padding:0.2em; border-radius: 3px; }
#login-form button { flex: 1 0 auto; display: block; margin: 0.4em 0; padding: 0.2em; border: 1px solid <?=GREEN_DARK?>; background: <?=GREEN_DARK?>; color: <?=WHITE?>; border-radius: 3px; cursor: pointer; }
#login-form button:hover { background: <?=GREEN?>; }

/** SIDEBAR CALENDAR LIST *****************************************************/
#sidebar-calendar-list { font-size: 0.9em; padding: 0.5em 0;}
#sidebar-calendar-list { font-size: 0.9em; padding: 0.5em 0;}

/** FOOTER ********************************************************************/
footer { <?=$C_FOOTER?> height: 200px; }

</style>
