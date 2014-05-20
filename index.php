<?php

require_once 'lib/utils.php';
require_once 'lib/xml.php';
require_once 'lib/pageelements.php';

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
$header->setLogoText("Shlendar");
$content = new PageSplit();
$content->setProperty('id', 'content');
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

$rootNode      = new XMLElement('html', 'xmlns', 'http://www.w3.org/1999/xhtml');
$headNode      = new XMLElement('head');
$titleNode     = new XMLElement('title');
$titleTextNode = new XMLText('Shlendar'.($titleText==NULL ? '' : ' - '.$titleText));
$charsetNode   = new XMLElement('meta', 'http-equiv', 'content-type', 'content', 'text/html; charset=utf-8');
$styleNode     = new XMLElement('link', 'rel', 'stylesheet', 'type', 'text/css', 'href', 'css/style.php');
$fontNode      = new XMLElement('link', 'rel', 'stylesheet', 'type', 'text/css', 'href', 'http://fonts.googleapis.com/css?family=Ubuntu:400,300');
$iconsNode     = new XMLElement('link', 'rel', 'stylesheet', 'type', 'text/css', 'href', 'http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css');

$bodyNode      = new XMLElement('body', 'class', ''.$action);
$footerNode    = new XMLElement('footer');

$rootNode->addChild($headNode);
	$headNode->addChild($titleNode);
		$titleNode->addChild($titleTextNode);
	$headNode->addChild($charsetNode);
	$headNode->addChild($styleNode);
	$headNode->addChild($fontNode);
	$headNode->addChild($iconsNode);
$rootNode->addChild($bodyNode);
	$bodyNode->addChild($header->toXML());
	$bodyNode->addChild($content->toXML());
	$bodyNode->addChild($footerNode);

$printer = new XMLPrinter();
echo $printer->createString($rootNode);
