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

const auto = 'auto';

const row    = 'row';
const column = 'column';

const wrap   = 'wrap';
const nowrap = 'nowrap';

const stretch      = 'stretch';
const center       = 'center';
const start        = 'flex-start';
const end          = 'flex-end';
const spacebetween = 'space-between';
const spacearound  = 'space-around';

function displayFlex($direction=NULL, $wrap=NULL, $itemAlignment=NULL, $justification=NULL, $contentAlignment=NULL) {
	$s = 'display: flex; display: -webkit-flex';
	if ($direction)        { $s .= "flex-direction: $direction; -webkit-flex-direction: $direction;"; }
	if ($wrap)             { $s .= "flex-wrap: $wrap; -webkit-flex-wrap: $wrap;"; }
	if ($itemAlignment)    { $s .= "align-items: $itemAlignment; -webkit-align-items: $itemAlignment;"; }
	if ($justification)    { $s .= "justify-content: $justification; -webkit-justify-content: $justification;"; }
	if ($contentAlignment) { $s .= "align-content: $contentAlignment; -webkit-align-content: $contentAlignment;"; }
	return $s;
}

function flex($grow=1, $shrink=0, $basis=auto) {
	return "flex: $grow $shrink $basis; -webkit-flex: $grow $shrink $basis;";
}

?>
<style>
	
*{border:0px;padding:0px;margin:0px;font-size:100%;font-family:inherit;font-weight:inherit;text-decoration:inherit;color:inherit;background:transparent;box-sizing:inherit;}
*{border:0px;padding:0px;margin:0px;font-size:100%;font-family:inherit;font-weight:inherit;text-decoration:inherit;color:inherit;background:transparent;box-sizing:inherit;}

h1, h2, h3, h4, h5, h6 { <?=FONT_HEADLINE?> }

html { height: 100%; <?=FONT_PARAGRAPH?> }
body { min-height: 100%; <?=$C_BASE?> }

/** HEADER, CONTENT AND FOOTER ************************************************/
body   { display: flex; flex-flow: column nowrap; }
header { flex: 0 0 auto; <?=$C_HEADER?> }
main   { flex: 1 0 auto; }
footer { flex: 0 0 auto; <?=$C_FOOTER?> }

/** HEADER ********************************************************************/
header { display: flex; flex-flow: row nowrap; justify-content: space-between; }
header .logo           { display: flex; }
header .header-actions { display: flex; }

header .logo a { display: flex; align-items: center; transition: all 0.30s ease-in-out; }
header .logo a:hover { <?=$C_HEADER_HOVER?> }
header .logo .icon { width: 64px; height: 64px; background: url('../img/logo.png'); }
header .logo .text { <?=FONT_HEADLINE?> font-size: 2em; padding-right: 0.5em; }

header .header-actions a { display: flex; align-items: center; transition: all 0.30s ease-in-out; }
header .header-actions a:hover { <?=$C_HEADER_HOVER?> }
header .header-actions a span { display: block; <?=FONT_HEADLINE?> font-size:1.2em; padding: 0.5em; }

/** MAIN **********************************************************************/
main { display: flex; flex-flow: column nowrap; background: red; }
#main-columns { flex: 1 0 auto; display: flex; flex-flow: row wrap; align-items: stretch; background:blue; }
#sidebar { flex: 1 0 auto; }
#content { flex: 100000 1 240px; min-width: 240px; }

#content .list { }
#content .list-item { display: flex}
#content .list-item-name { <?=flex(1,0,auto)?> }
#content .list-item-

/** SIDEBAR *******************************************************************/
#sidebar { <?=$C_SIDEBAR?> }
#sidebar > div > h2 { text-align:center; padding: 0.2em; border-bottom: 1px solid <?=GRAY_DARKER?>; <?=FONT_IMPORTANT?> font-size:1.0em; }
#sidebar > div > div { margin-bottom: 0.5em; }
/** SIDEBAR CALENDAR **********************************************************/
#sidebar-calendar { margin: 0 auto; margin-top: 0.5em; max-width:20em; }
#sidebar-calendar-header { display: table; margin-bottom:0.5em; width:100%; box-sizing: border-box; font-size:0.85em; border-bottom: 1px solid <?=GRAY_DARKER?>;}
#sidebar-calendar-header .previous { display: table-cell; width:49%; text-align: left; }
#sidebar-calendar-header .title    { display: table-cell; width:2%; white-space: nowrap; }
#sidebar-calendar-header .next     { display: table-cell; width:49%; text-align: right; }
#sidebar-calendar-header a { display: block; height: 100%; width: 100%; }
#sidebar-calendar-header .previous a:hover { background: <?=GRAY_LIGHTER?>; background: linear-gradient(to right, rgba(236,240,241,1) 0%,rgba(236,240,241,0) 100%); }
#sidebar-calendar-header .next     a:hover { background: <?=GRAY_LIGHTER?>; background: linear-gradient(to left, rgba(236,240,241,1) 0%,rgba(236,240,241,0) 100%); }
#sidebar-calendar-entries { display: table; width: 100%; box-sizing: border-box; text-align: center; padding: 0 0.5em; }
#sidebar-calendar-entries > div { display: table-row; }
#sidebar-calendar-entries > div > div { display: table-cell;  }
#sidebar-calendar-entries > div > div > a { display:block; font-size: 0.85em; padding: 0.2em 0.4em; color: <?=GRAY_DARKER?>; }
#sidebar-calendar-entries .current-month a { color: <?=BLUE_DARKER?>; }
#sidebar-calendar-entries .current-month.current a { color: <?=WHITE?>; background: <?=GRAY_DARKER?>;}
#sidebar-calendar-entries .current-month.selected a { color: <?=WHITE?>; background: <?=ORANGE?>; }
#sidebar-calendar-entries > div > div > a:hover { background: <?=GRAY_LIGHTER?>; }
#sidebar-calendar-entries .dayrow { text-transform: uppercase; <?=FONT_HEADLINE?> font-size:0.75em; }

/** SIDEBAR ACTIONS ***********************************************************/
#sidebar-actions { }
#sidebar-actions .container { }
#sidebar-actions .action a { display: block; }
#sidebar-actions .action a:hover { background: <?=GRAY_LIGHTER?>; }
#sidebar-actions .action a span { line-height: 2em; font-size: 0.85em; }

/** SIDEBAR CALENDAR LIST *****************************************************/
#sidebar-calendars { }
#sidebar-calendars .container { }
#sidebar-calendars .action a { display: block; }
#sidebar-calendars .action a:hover { background: <?=GRAY_LIGHTER?>; }
#sidebar-calendars .action a span { line-height: 2em; font-size: 0.85em; }

/** CONTENT *******************************************************************/
#content { <?=$C_CONTENT?> <?=FONT_PARAGRAPH?> background: <?=GRAY_LIGHTER?> }
#content > div { display: flex; flex-flow: row wrap; justify-content:flex-start; align-items: stretch; padding: 0.5em; }
#content > div > div { flex: 1 0 auto; max-width:40em; margin: 0.5em; padding: 1em; background: <?=WHITE?>; border: 1px solid <?=GRAY_LIGHT?>; }
#content h1 { margin: 0.7em 0.625em 0em 0.625em; font-size: 1.6em; <?=$C_CONTENT_HEAD?> <?=FONT_HEADLINE?> }
#content h2 { margin-bottom: 0.9em; font-size: 1.4em; <?=$C_CONTENT_HEAD?> <?=FONT_HEADLINE?> }

#content input[type=text], #content input[type=password] { font-size: 0.9em; padding:0.1em 0.3em; color: <?=BLUE_DARKER?>; border: 1px solid <?=GRAY_LIGHT?>; border-radius: 5px; margin: 0.2em 0; transition: all 0.30s ease-in-out; }
#content input[type=text]:focus, #content input[type=password]:focus { border: 1px solid <?=GRAY_DARK?>; background: <?=WHITE?>; }
#content button[type=submit] { <?=FONT_IMPORTANT?> font-size: 0.9em; padding:0.1em 0.3em; border: 1px solid <?=GRAY_DARK?>; border-radius: 5px; background: <?=GRAY_DARK?>; color: <?=WHITE?>; cursor: pointer; transition: all 0.30s ease-in-out; }
#content button[type=submit]:hover { background: <?=GRAY_DARKER?>; }
#content button[type=submit].submit { border: 1px solid <?=GREEN_DARK?>; background: <?=GREEN_DARK?>; }
#content button[type=submit].submit:hover { background: <?=GREEN?>; }
#content button[type=submit].delete { border: 1px solid <?=RED_DARK?>; background: <?=RED_DARK?>; }
#content button[type=submit].delete:hover { background: <?=RED?>; }
#content button[type=submit].edit { border: 1px solid <?=BLUE_LIGHT?>; background: <?=BLUE_LIGHT?>; }
#content button[type=submit].edit:hover { background: <?=BLUE_LIGHTER?>; }

/** LOGIN *********************************************************************/
#login { box-sizing: border-box; }
#login .login-failed { padding: 0.5em; margin-bottom: 0.7em; <?=$C_WARNING?> <?=FONT_IMPORTANT?> }
#login .login-failed p:first-child { margin-bottom: 0.5em; font-style: italic; }
#login .login-failed p:last-child { font-size: 0.8em; }

#login .login-form { display: flex; flex-flow: row wrap; justify-content: flex-start; align-items: baseline; margin:-0.3em -0.6em; }
#login .login-userpass { flex: 1 1 auto; padding: 0.3em 0.6em; }
#login .login-button   { flex: 1 0 auto; padding: 0.3em 0.6em; }

#login .login-userpass { display: flex; flex-flow: row wrap; justify-content: flex-start; align-items: baseline; margin:-0.3em -0.6em; }
#login .login-user { flex: 1 1 auto; padding: 0.3em 0.6em; }
#login .login-pass { flex: 1 1 auto; padding: 0.3em 0.6em; }

#login .login-user { display: flex; flex-flow: row wrap; justify-content: flex-start; align-items: baseline; margin:-0.2em; }
#login .login-user > div   { flex: 0 0 auto; margin: 0.2em; min-width: 4em; text-align: right; }
#login .login-user > input { flex: 1 1 auto; margin: 0.2em; }

#login .login-pass { display: flex; flex-flow: row wrap; justify-content: flex-start; align-items: baseline; margin:-0.2em; }
#login .login-pass > div   { flex: 0 0 auto; margin: 0.2em; min-width: 4em; text-align: right; }
#login .login-pass > input { flex: 1 1 auto; margin: 0.2em; }

#login .login-button button { width: 100%; }

/** GROUP MANAGEMENT **********************************************************/
#group-management { box-sizing: border-box; }
#group-management .group-list-container  { display: flex; flex-flow: column nowrap; align-items: stretch; margin: -0.3em -0.6em; }
#group-management .group-list-item { flex: 1 0 auto; padding: 0.3em 0.6em; }

#group-management .group-list-item { display: flex; flex-flow: row wrap; justify-content: flex-start; align-items: baseline; margin: -0.3em -0.6em; }
#group-management .group-list-item-name { flex: 100000 1 auto; padding: 0.3em 0.6em; font-style: italic; }
#group-management .button-group         { flex: 1 0 auto; padding: 0.3em 0.6em; }

#group-management .button-group { display: flex; flex-flow: row wrap; justify-content: flex-start; align-items: baseline; margin: -0.2em; }
#group-management .groupitem-edit   { flex: 1 0 auto; padding: 0.2em; }
#group-management .groupitem-delete { flex: 1 0 auto; padding: 0.2em; }

#group-management .groupitem-edit   button { width: 100%; }
#group-management .groupitem-delete button { width: 100%; }

#group-management .group-insert-form { display: flex; flex-flow: row wrap; justify-content: flex-start; align-items: baseline; margin: -0.2em; }
#group-management .group-insert-form .group-insert-name-container   { flex: 100000 1 auto; padding: 0.2em; }
#group-management .group-insert-form .group-insert-button-container { flex: 1 1 auto; padding: 0.2em; }

#group-management .group-insert-form .group-insert-name { width:100%; }
#group-management .group-insert-form .submit            { width:100%; }

/** CALENDAR MANAGEMENT *******************************************************/
#calendar-management { box-sizing: border-box; }
#calendar-management .calendar-list-container{ display: flex; flex-flow: column nowrap; align-items: stretch; margin: -0.3em -0.6em; }
#calendar-management .calendar-list-item{ display: flex; flex-direction: column; flex-flow: column nowrap; align-items: stretch; margin: -0.3em -0.6em; }

/** FOOTER ********************************************************************/
footer { <?=$C_FOOTER?> height: 200px; }

</style>
