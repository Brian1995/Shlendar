<?php

require_once 'lib/db.php';
require_once 'lib/pageelements.php';
require_once 'lib/utils.php';
require_once 'lib/xml.php';

mb_internal_encoding("UTF-8");
setlocale(LC_ALL, 'de_DE.utf-8');
URL::setBasePath('projekt');
session_start();

$dbConnection = new DatabaseConnection();
$dbConnection->connect();

$logged_in = Session::isLoggedIn();

$url_current = URL::urlFromCurrent();
$url_start = URL::urlFromRelativePath('index.php');
$url_start->setQueryParameter('action', NULL);
$action = $url_current->getQueryParameter('action');

$header = new PageHeader();
$header->setLogo(new PageImage(URL::urlFromRelativePath('img/logo.png'), $url_start));
$content = new PageSplit();
$sidebar = new PageSidebar('actions');

$titleText = NULL;
		
switch ($action) {
	case 'login':
		$titleText = 'Login';
		$url = new URL($url_start);
		$url->setQueryParameter('action', 'login_exec');
		$content->setCenter(new PageLogin($url));
		break;
	case 'login_exec':
		Session::execLogin($dbConnection);
		break;
	case 'logout':
		Session::logout();
		break;
	default:
		$titleText = 'Startseite';
		$content->setLeft($sidebar);
		$content->setCenter(new PageText('xxx'));
		
		$calendar = new PageCalendar();
		$calendar->setViewDate(new Date($url_current->getQueryParameter('viewDate')));
		
        $calendars = new PageCalendarList($dbConnection);
		
		$sidebar->addChild($calendar);
		$sidebar->addChild($calendars);
                
		break;
}
$header->setTitle(new PageText($titleText));


echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">' . "\r\n\r\n";

$root = new XMLElement('html');
$root->addAttribute('xmlns', 'http://www.w3.org/1999/xhtml');
$root->addChild($head = new XMLElement('head'));
$head->addChild($title = new XMLElement('title'));
$title->addChild(new XMLText('Shlendar'.($titleText==NULL ? '' : ' - '.$titleText)));
$head->addChild($meta_charset = new XMLElement('meta'));
$meta_charset->addAttribute('http-equiv', 'content-type')->addAttribute('content', 'text/html; charset=utf-8');
$head->addChild($link_style = new XMLElement('link'));
$link_style->addAttribute('rel', 'stylesheet')->addAttribute('type', 'text/css')->addAttribute('href', 'css/style.php');
$head->addChild($link_font = new XMLElement('link'));
$link_font->addAttribute('rel', 'stylesheet')->addAttribute('type', 'text/css')->addAttribute('href', 'http://fonts.googleapis.com/css?family=Open+Sans:400,300');
$root->addChild($body = new XMLElement('body'));
$body->addAttribute('class', ''.$action);
$body->addChild($page = new XMLElement('div'));
$page->addAttribute('id', 'page');
$page->addChild($header->toXML());
$page->addChild($pageContent = new XMLElement('div', 'page-content'));
$pageContent->addChild($content->toXML());


$printer = new XMLPrinter();
echo $printer->createString($root);
