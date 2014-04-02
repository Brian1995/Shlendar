<?php

require_once 'session.php';

function render_link($content=NULL, $url=NULL, $class=NULL) {
	if (empty($content)) {
		return '';
	}
	if (empty($url)) {
		return '<span class="blind-link">'.$content.'</span>';
	}
	return '<a href="'.$url.'"'.(empty($class) ? '' : 'class="'.$class.'"').'><span class="link">'.$content.'</span></a>';
}

function render_content_area($left=NULL, $center=NULL, $right=NULL) {
	$s = '';
	$has_left   = !empty($left);
	$has_center = !empty($center);
	$has_right  = !empty($right);
	$not_empty = $has_left || $has_center || $has_right;
	
	if ($not_empty) {
		$s .= '<div class="table"><div class="row">';
	}
	if ($has_left) {
		$s .= '<div class="cell left">'.$left.'</div>';
	}
	if ($has_center) {
		$s .= '<div class="cell center">'.$center.'</div>';
	}
	if ($has_right) {
		$s .= '<div class="cell right">'.$right.'</div>';
	}
	if ($not_empty) {
		$s .= '</div></div>';
	}
	return $s;
}

function render_page_content($content=NULL) {
	if (empty($content)) {
		return '';
	} else {
		return '<div id="page-content">'.$content.'</div>';
	}
}

function render_start_page() {
	return 'Startseite';
}

function render_login_form() {
	$s = '<div class="login"><div class="login2">';
	
	if (Session::loginFailed()) {
		$s .= '<div class="login-failed"><div class="login-failed2">';
		$s .= '<p class="login-failed-head">Anmeldung fehlgeschlagen</p>';
		$s .= '<p class="login-failed-body">Benutzername oder Passwort falsch</p>';
		$s .= '</div></div>';
	}
	
	$action = url_set_query_parameter(url_relative('index.php'), 'action', 'login_exec');
	
	$s .= '<form class="login-form" action="'.$action.'" method="POST"><div class="login-form">';
	$s .= '<div class="login-username"><p>Benutzername</p><input type="text"     name="username" /></div>';
	$s .= '<div class="login-password"><p>Passwort</p>    <input type="password" name="password" /></div>';
	$s .= '<div class="submit"><input type="submit"   value="Anmelden" /></div>';
	$s .= '</div></form>';
	
	$s .= '</div></div>';
	return $s;
}

function render_page_header($logged_in=FALSE) {
	$s = '<div id="page-header" class="table"><div class="row">';
	
	$start_page_link = url_set_path_parameter(url_full(), 'index.php');
	$start_page_link = url_set_query_parameter($start_page_link, 'action', NULL);
	$s .= '<div class="cell left">'.render_link('Startseite', $start_page_link).'</div>';
	
	$s .= '<div class="cell center"></div>';
	
	$label = $logged_in ? 'Abmelden' : 'Anmelden';
	$link  = url_set_query_parameter(url_relative('index.php'), 'action', $logged_in ? 'logout' : 'login');
	$s .= '<div class="cell right">'.render_link($label, $link).'</div>';
	
	$s .= '</div></div>';
	return $s;
}


function render_header_row($logged_in) {
?>
<div id="page-header" class="table">
	<div class="row">
		<div class="cell left">
			<a id="home" href="<?= url_relative('index.php') ?>">
				Startseite
			</a>
		</div>
		<div class="cell center">
			Kopfzeile
		</div>
		<div class="cell right">
			<a id="<?=$logged_in ? 'logout' : 'login' ?>" href="index.php?action=<?= $logged_in ? 'logout' : 'login' ?>">
				<?= $logged_in ? "Abmelden" : "Anmelden"?>
			</a>
		</div>
	</div>
</div>
<?php
}

function render_actions($type="") { 
	$createCalendarUrl = url_set_query_parameter(url_relative("action.php"), "action", "create-calendar");
?>
	<div id="actions" class="block">
		<div class="header">Aktionen</div>
		<div class="content">
			<?php
			switch($type) {
				case "":
					?>
					
					<?php
					break;
			}
			/*
			<a href="<?=$createCalendarUrl?>">Neuer Kalender</a>
			<a href="">Neuer Termin</a>
			*/
			?>
		</div>
	</div>
<?php

}

?>