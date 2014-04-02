<?php

include_once 'library.php';

$logged_in = Session::isLoggedIn();
$action = url_get_query_parameter(url_full(), 'action');

beginPage();
echo render_page_header($logged_in);

$center_content = NULL;
$left_content = NULL;

switch($action) {
	case 'login':
		$center_content = render_login_form();
		break;
	case 'login_exec':
		db_exec_login();
		break;
	case 'logout':
		db_exec_logout();
		break;
	default:
		$center_content = $logged_in ? NULL : render_start_page();
		break;
}

$content = render_content_area($left_content, $center_content);
echo render_page_content($content);

endPage();
