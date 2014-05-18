<?php

header("Content-type: text/css");

const WHITE        = 'rgba(255,255,255,1.0)';

const GRAY_LIGHTER = 'rgba(236,240,241,1.0)';
const GRAY_LIGHT   = 'rgba(189,195,199,1.0)';
const GRAY_DARK    = 'rgba(149,165,166,1.0)';
const GRAY_DARKER  = 'rgba(127,140,141,1.0)';

const BLUE_DARK    = 'rgba( 52, 73, 94,1.0)';
const BLUE_DARKER  = 'rgba( 44, 62, 80,1.0)';

const ORANGE       = 'rgba(230,126, 34,1.0)';

const FONT_HEADLINE  = "font-family: 'Open Sans', sans-serif; font-weight:300;";
const FONT_PARAGRAPH = "font-family: 'Open Sans', sans-serif; font-weight:400;";

$C_BASE         = "background: rgba(255,255,255,1.0); color: rgba( 70, 70, 70,1.0);";
$C_HEADER       = "background: rgba( 44, 62, 80,1.0); color: rgba(255,255,255,1.0);";
$C_HEADER_HOVER = "background: rgba( 52, 73, 94,1.0); color: rgba(255,255,255,1.0);";
$C_FOOTER       = "background: rgba( 44, 62, 80,1.0); color: rgba(255,255,255,1.0);";
$C_SIDEBAR      = "background: rgba(189,195,199,1.0); color: rgba( 44, 62, 80,1.0);";

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
header .logo .text { <?=$FONT_HEADLINE?> font-size: 2em; padding-right: 0.5em; }

header .header-actions a { display: flex; align-items: center; }
header .header-actions a:hover { <?=$C_HEADER_HOVER?> }
header .header-actions a span { display: block; <?=$FONT_HEADLINE?> font-size:1.25em; padding: 0.5em; }

/** MAIN **********************************************************************/
main { display: flex; }
#main-columns { flex: 1 0 auto; display: flex; flex-flow: row wrap; align-content: flex-start; }
#sidebar { flex: 1 1 auto; }
#content { flex: 100000 1 auto; min-width: 320px; }

/** SIDEBAR *******************************************************************/
#sidebar { <?=$C_SIDEBAR?> }
#sidebar h2 { display: none; }

/** SIDEBAR CALENDAR **********************************************************/
#sidebar-calendar { padding: 0.5em; margin: 0 auto; margin-top: 0.5em; max-width:20em; }
#sidebar-calendar-header { display: flex; flex-flow: row nowrap; justify-content: space-between; padding: 0 0.5em; }
#sidebar-calendar-header .title { flex: 0 1 auto; }
#sidebar-calendar-entries { display: table; width: 100%; box-sizing: border-box; text-align: center; padding: 0 0.5em; }
#sidebar-calendar-entries > div { display: table-row; }
#sidebar-calendar-entries > div > div { display: table-cell;  }
#sidebar-calendar-entries > div > div > a { display:block; font-size: 0.9em; padding: 0.2em 0.4em; color: <?=GRAY_DARKER?>; }
#sidebar-calendar-entries .current-month a { color: <?=BLUE_DARKER?>; }
#sidebar-calendar-entries .current-month.current a { color: <?=WHITE?>; background: <?=GRAY_DARKER?>;}
#sidebar-calendar-entries .current-month.selected a { color: <?=WHITE?>; background: <?=ORANGE?>; }
#sidebar-calendar-entries > div > div > a:hover { background: <?=GRAY_LIGHTER?>; }
#sidebar-calendar-entries .dayrow { text-transform: uppercase; <?=FONT_HEADLINE?> font-size:0.8em; }

/** FOOTER ********************************************************************/
footer { <?=$C_FOOTER?> height: 200px; }

</style>