<?php

require_once 'lib/utils.php';
require_once 'lib/xml.php';
require_once 'lib/pageelements.php';

mb_internal_encoding("UTF-8");
setlocale(LC_ALL, 'de_DE.utf-8');
URL::setBasePath('projekt');
session_start();
#Session::fixMimeType();

$dbConnection = new DatabaseConnection('localhost', 'projekt', 'projekt', 'projekt');
$dbConnection->connect();

$logged_in = Session::isLoggedIn();

$url_current = URL::createCurrent();
$url_start = URL::createStatic($url_current);

$action = $url_current->getDynamicQueryParameter('action');

/** MAIN STRUCTURE *********************************************************** */
$body = new PageContainer('body');
$body->setProperties('class', '' . $action);
$header = new PageContainer('header');
$header->setProperties('id', 'header');
$main = new PageContainer('main');
$main->setProperties('id', 'main');
$footer = new PageContainer('footer');
$footer->setProperties('id', 'footer');

$body->addChild($header);
$body->addChild($main);
$body->addChild($footer);

$mainColumns = new PageContainer('div');
$mainColumns->setProperties('id', 'main-columns');
$main->addChild($mainColumns);

$sidebar = new PageContainer('div');
$sidebar->setProperties('id', 'sidebar');
$content = new PageContainer('div');
$content->setProperties('id', 'content');

/* PAGE HEADER *************************************************************** */
$header->addChild(new PageLogo('Shlendar', $url_start));
$header->addChild($topActions = new PageContainer());
$topActions->setProperty('class', 'header-actions');
if ($logged_in) {
    $topLoginActionText = 'Ausloggen';
    $topLoginActionAction = 'logout';
} else {
    $topLoginActionText = 'Einloggen';
    $topLoginActionAction = 'login';
}
$topLoginActionUrl = new URL($url_start);
$topLoginActionUrl->setDynamicQueryParameter('action', $topLoginActionAction);
$topLoginAction = new PageLink(new PageText($topLoginActionText), $topLoginActionUrl);
$topLoginAction->setProperty('id', 'header-actions-login');
$topActions->addChild($topLoginAction);

/* HELPERS ******************************************************************* */

function ensureLogin() {
    if (!Session::isLoggedIn()) {
        global $url_current, $url_start;
        $loginUrl = new URL($url_start);
        $loginUrl->setDynamicQueryParameter('action', 'login');
        $loginUrl->setDynamicQueryParameter('referrer', $url_current);
        $loginUrl->redirect();
        exit(0);
    }
}

function addSidebarCalendar() {
    global $sidebar, $url_current;
    $calendar = new PageCalendar();
    $calendar->setViewDate(new Date($url_current->getStaticQueryParameter('viewDate')));
    $sidebar->addChild($calendar);
}

function addSidebarActions() {
    global $sidebar;
    $sidebarActions = new PageContainer('div', 'id', 'sidebar-actions');
    $sidebarActions->addChild(new PageTextContainer(PageTextContainer::H2, 'Aktionen'));
    $sidebarActions->addChild($sidebarActionsContainer = new PageContainer('div', 'class', 'action-container'));

    $url_calendars = URL::createStatic();
    $url_calendars->setDynamicQueryParameter('action', 'manage-calendars');
    $sidebarActionsContainer->addChild(new PageAction($url_calendars, 'Kalender verwalten', new PageFontIcon('calendar-o', PageFontIcon::NORMAL, TRUE)));

    $url_groups = URL::createStatic();
    $url_groups->setDynamicQueryParameter('action', 'manage-groups');
    $sidebarActionsContainer->addChild(new PageAction($url_groups, 'Gruppen verwalten', new PageFontIcon('users', PageFontIcon::NORMAL, TRUE)));
    $sidebar->addChild($sidebarActions);
}

function addSidebarCalendarList() {
    global $sidebar, $dbConnection;
    $calendars = new PageCalendarList($dbConnection);
    $sidebar->addChild($calendars);
}

/* CONTENT ******************************************************************* */
$titleText = NULL;

switch ($action) {

    case 'login':
        $titleText = 'Login';
        $url = URL::createStatic();
        $url->setDynamicQueryParameter('action', 'login_exec');
        $url->setDynamicQueryParameter('referrer', $url_current->getDynamicQueryParameter('referrer'));
        $content->addChild(new PageLogin($url));
        addSidebarCalendar();
        break;

    case 'login_exec':
        $referrer = $url_current->getDynamicQueryParameter('referrer');
        $referrerUrl = $referrer ? URL::create($referrer) : NULL;
        Session::execLogin($dbConnection, $referrerUrl);
        break;

    case 'logout':
        Session::logout();
        break;

    case 'manage-groups':
        ensureLogin();
        $titleText = 'Gruppen Verwalten';
        addSidebarCalendar();
        addSidebarActions();
        addSidebarCalendarList();
        $groupManagement = new PageGroupManagement($dbConnection);
        $content->addChild($groupManagement);
        break;

    case 'manage-calendars':
        ensureLogin();
		$titleText = "Kalender verwalten";
        addSidebarCalendar();
        addSidebarActions();
        addSidebarCalendarList();
        $calendarManagement = new PageCalendarManagement($dbConnection);
        $content->addChild($calendarManagement);
        break;

    case 'insert-group':
        ensureLogin();
        $referrer = $url_current->getDynamicQueryParameter('referrer');
        if (PageGroupManagement::insertGroup($dbConnection)) {
            URL::create($referrer)->redirect();
        } else {
            // TODO error page
        }
        break;

    case 'insert-calendar':
        ensureLogin();
        $referrer = $url_current->getDynamicQueryParameter('referrer');
        if (PageCalendarManagement::insertCalendar($dbConnection)) {
            URL::create($referrer)->redirect();
        } else {
            
        }
        break;

    case 'delete-group':
        ensureLogin();
        $userId = Session::getUserID();
        $groupId = $url_current->getDynamicQueryParameter('id');
        $referrer = $url_current->getDynamicQueryParameter('referrer');
        if (PageGroupManagement::isGroupOwner($dbConnection, $userId, $groupId) &&
                PageGroupManagement::deleteGroup($dbConnection, $userId, $groupId)) {
            URL::create($referrer)->redirect();
        } else {
            // TODO error page
        }
        break;

    case 'delete-calendar':
        ensureLogin();
        $calendarID = $url_current->getDynamicQueryParameter('id');
        $referrer = $url_current->getDynamicQueryParameter('referrer');
        if(PageCalendarManagement::deleteCalendar($dbConnection, $calendarID)){
            URL::create($referrer)->redirect();
        } else {
        }
        break;

    case 'edit-group':
        ensureLogin();
        $titleText = 'Gruppe bearbeiten';
        addSidebarCalendar();
        addSidebarActions();
        addSidebarCalendarList();

        $groupId = $url_current->getDynamicQueryParameter('id');
        $userId = Session::getUserID();

        $editor = new PageGroupEditor($dbConnection, $groupId, $userId);
        $content->addChild($editor);
        break;
	
	case 'remove-user-from-group':
		ensureLogin();
		
		$group_user_relation_id = $url_current->getDynamicQueryParameter('id');
		PageGroupEditor::removeMember($group_user_relation_id);
		
		URL::create($url_current->getDynamicQueryParameter('referrer'))->redirect();
		break;
    
    case 'edit-calendar':
        ensureLogin();
        addSidebarCalendar();
        addSidebarActions();
        addSidebarCalendarList();
		$calendarName = $url_current->getDynamicQueryParameter('name');
        $calendarID = $url_current->getDynamicQueryParameter('id');
		
		
        $titleText = $calendarName." bearbeiten";
        $editor = new PageCalendarEditor($dbConnection, $calendarID);
        $content->addChild($editor);
        break;
    
    case 'add-group-to-calendar':
        ensureLogin();
		
		$referrer = $url_current->getDynamicQueryParameter('referrer');
		
		$calendar = $url_current->getDynamicQueryParameter('id');
		$group = $url_current->getDynamicQueryParameter('group');
		PageCalendarEditor::addGroupToCalendar($dbConnection, $calendar, $group, 1);
		
		URl::create($referrer)->redirect();
        break;
	
	case 'remove-group-from-calendar':
		ensureLogin();
		$referrer = $url_current->getDynamicQueryParameter('referrer');
		var_dump($referrer);
		$id = $url_current->getDynamicQueryParameter('id');
		PageCalendarEditor::removeGroupFromCalendar($dbConnection, $id);
		var_dump("bla");
//		URL::create($referrer)->redirect();
		break;
		
    case 'listAppointments':
        ensureLogin();
        $titleText = 'Termine';
        addSidebarCalendar();
        addSidebarActions();
        addSidebarCalendarList();

        $calendar = $url_current->getDynamicQueryParameter('calendar');
        $app = new PageAppointmentList($dbConnection, $calendar);
        $url = URL::createStatic();
        $url->setDynamicQueryParameter('action', 'addAppointment');
        $add = new PageAddAppointment($url);
        $content->addChild($app);
        $content->addChild($add);
        break;

    case 'deleteAppointment':
        ensureLogin();
        $id = $url_current->getDynamicQueryParameter('appointment');
        $result = $dbConnection->query("DELETE FROM appointments WHERE id = '%s';", $id);
        $url = URL::createStatic();
        $url->setDynamicQueryParameter('action', 'listAppointments');
        $url->redirect();
        break;

    case 'addAppointment':
        $i = PageAddAppointment::addApppointment($dbConnection);
        break;

    default:
        addSidebarCalendar();
        if ($logged_in) {
            addSidebarActions();
            addSidebarCalendarList();
        }
        if ($logged_in) {
            $titleText = 'Willkommen ' . Session::getUserName();
        } else {
            $titleText = 'Willkommen bei Shlendar';
        }
        $content->addChild(new PageTextContainer(PageTextContainer::P, 'Ein fürchterlicher Kalender...'));
        break;
}

/* PAGE CONSTRUCTION ********************************************************* */

if ($sidebar->hasChildren()) {
    $mainColumns->addChild($sidebar);
}
if ($content->hasChildren()) {
    $content->addChild(new PageTextContainer(PageTextContainer::H1, $titleText), 0);
    $mainColumns->addChild($content);
}

/* echo '<?xml version="1.0" encoding="UTF-8" ?>' . "\r\n";
  echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">' . "\r\n\r\n"; */
echo '<!DOCTYPE HTML>';

$rootNode = new XMLElement('html', 'xmlns', 'http://www.w3.org/1999/xhtml');
$headNode = new XMLElement('head');
$titleNode = new XMLElement('title');
$titleTextNode = new XMLText('Shlendar' . ($titleText == NULL ? '' : ' - ' . $titleText));

$rootNode->addChild($headNode);
$headNode->addChild($titleNode);
$titleNode->addChild($titleTextNode);

//$headNode->addChild(new XMLElement('meta', 'http-equiv', 'content-type', 'content', 'text/html; charset=utf-8'));
$headNode->addChild(new XMLElement('meta', 'name', 'viewport', 'content', 'width=device-width, initial-scale=1'));
$headNode->addChild(new XMLElement('link', 'rel', 'stylesheet', 'type', 'text/css', 'href', 'css/style.php'));
$headNode->addChild(new XMLElement('link', 'rel', 'stylesheet', 'type', 'text/css', 'href', 'http://fonts.googleapis.com/css?family=Open+Sans:400,300&subset=latin,latin-ext'));
$headNode->addChild(new XMLElement('link', 'rel', 'stylesheet', 'type', 'text/css', 'href', '//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css'));
$headNode->addChild(new XMLElement('link', 'rel', 'stylesheet', 'type', 'text/css', 'href', 'js/jquery.datetimepicker.css'));
$headNode->addChild(new XMLElement('script', 'type', 'text/javascript', 'src', 'js/jquery-2.1.1.js'));
$headNode->addChild(new XMLElement('script', 'type', 'text/javascript', 'src', 'js/jquery.datetimepicker.js'));
$headNode->addChild(new XMLElement('script', 'type', 'text/javascript', 'src', 'js/script.js'));

$rootNode->addChild($body->toXML());

$printer = new XMLPrinter();
echo $printer->createString($rootNode);
