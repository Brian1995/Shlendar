<?php

include_once 'library.php';

$logged_in = Session::isLoggedIn();
$action = url_get_query_parameter(url_full(), 'action');

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
//		$center_content = $logged_in ? NULL : render_start_page();
		break;
}


echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">'."\r\n\r\n";

$root = new XMLElement('html');
$root->addAttribute('xmlns', 'http://www.w3.org/1999/xhtml');
$root->addChild($head = new XMLElement('head'));
$head->addChild($title = new XMLElement('title'));
$title->addChild(new XMLText('Seitentitel'));
$head->addChild($meta_charset = new XMLElement('meta'));
$meta_charset->addAttribute('http-equiv', 'content-type')->addAttribute('content', 'text/html; charset=utf-8');
$head->addChild($link_style = new XMLElement('link'));
$link_style->addAttribute('rel', 'stylesheet')->addAttribute('type', 'text/css')->addAttribute('href', 'css/style.css');
$root->addChild($body = new XMLElement('body'));

$url = URL::urlFromCurrent();
$url2 = URL::urlFromRelativePath('../css/style.css', $url);
$link = new Link(NULL, $url2);
$body->addChild($link->toXML());

$printer = new XMLPrinter();
echo $printer->createString($root);