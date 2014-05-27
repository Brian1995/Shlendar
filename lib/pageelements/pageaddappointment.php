<?php

/**
 * Description of pageaddappointment
 *
 * @author bussebr
 */
class PageAddAppointment extends PageContainer {

	/** @var URL */
    private $submitURL;

    public function __construct($submitURL) {
        parent::__construct('div');
        $this->setProperty('id', 'add-appointment');
        $this->submitURL = $submitURL;
    }

    public function toXML() {
        $titleLabel = new XMLElement('div');
        $titleLabel->addChild(new XMLText("Titel"));
        $title = new XMLElement('input', 'type', 'text', 'name', 'title', 'class', 'fill');

        $fromLabel = new XMLElement('div');
        $fromLabel->addChild(new XMLText("Von"));
        $from = new XMLElement('input', 'type', 'text', 'name', 'fromDate', 'id', 'datetimepicker-from', 'class', 'fill');

        $toLabel = new XMLElement('div');
        $toLabel->addChild(new XMLText("Bis"));
        $to = new XMLElement('input', 'type', 'text', 'name', 'toDate', 'id', 'datetimepicker-to', 'class', 'fill');

        $descriptionLabel = new XMLElement('div');
        $descriptionLabel->addChild(new XMLText("Beschreibung"));
        $description = new XMLElement('input', 'type', 'text', 'name', 'description', 'class', 'fill');

        $divTitle = new XMLElement('div', 'id', 'add-appointment-title', 'class', 'entry');
        $divTitle->addChild($titleLabel);
        $divTitle->addChild($title);

        $divFrom = new XMLElement('div', 'class', 'entry');
        $divFrom->addChild($fromLabel);
        $divFrom->addChild($from);

        $divTo = new XMLElement('div', 'class', 'entry');
        $divTo->addChild($toLabel);
        $divTo->addChild($to);

        $divDescription = new XMLElement('div', 'class', 'entry');
        $divDescription->addChild($descriptionLabel);
        $divDescription->addChild($description);

		$submitContainer = new XMLElement('div', 'class', 'entry');
        $submit = new PageButton('Hinzufügen', PageButton::STYLE_SUBMIT, PageFontIcon::create('plus-square'));
		$submit->setProperty('class', 'fill');
		$submitContainer->addChild($submit->toXML());

		$dateGroup = new XMLElement('div', 'class', 'group entry stretch');
		$dateGroup->addChild($divFrom);
		$dateGroup->addChild($divTo);
		
		$this->submitURL->setDynamicQueryParameter('referrer', URL::createCurrent());
		
        $form = new XMLElement('form', 'id', 'add-appointment', 'action', $this->submitURL, 'method', 'post');
        $form->addChild($divTitle);
        $form->addChild($dateGroup);
        $form->addChild($divDescription); 
		$form->addChild($submitContainer);

		$header = new XMLElement('h2');
		$header->addChild(new XMLText('Termin hinzufügen'));
		$container = new XMLElement('div', 'class', 'groupv');
		$container->addChild($form);
				
		$over = new XMLElement('div');
		$over->addChild($header);
		$over->addChild($container);
		
        $addAppointment = parent::toXML();
        $addAppointment->addChild($over);

        return $addAppointment;
    }
	
	public static function addApppointment($dbConnection) {
        if (filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_DEFAULT) === 'POST') {
            if (filter_has_var(INPUT_POST, 'title') && filter_has_var(INPUT_POST, 'fromDate') && filter_has_var(INPUT_POST, 'toDate') && filter_has_var(INPUT_POST, 'description')) {
                $title = filter_input(INPUT_POST, 'title');
                $from = filter_input(INPUT_POST, 'fromDate');
                $to = filter_input(INPUT_POST, 'toDate');
                $description = filter_input(INPUT_POST, 'description');
                $url = URL::createCurrent();
                $calendar = $url->getDynamicQueryParameter('calendar');
                $result = $dbConnection->query(
                        "INSERT INTO appointments (calendar_id, start_date, end_date, title, description)
					 VALUE ('%s', '%s', '%s', '%s', '%s');"
                        , $calendar, $from, $to, $title, $description);
                if (!$result) {
					echo mysql_error();
                }
            }
        }
        return false;
    }
	
	public static function userCanEdit(DatabaseConnection $db, $user, $calendar){
		$user_id = $db->query("SELECT user_id FROM calendars WHERE id = '%s';", $calendar);
		$a = mysql_fetch_array($user_id);
		if($a[0] == $user){ return true; }
		
		$result = $db->query("SELECT group_id FROM group_calendar_relations WHERE calendar_id = '%s' AND group_id IN "
				. "(SELECT group_id FROM group_user_relations WHERE user_id = '%s');", $calendar, $user);
		while ($a = mysql_fetch_array($result)){
			if(PageCalendarEditor::groupCanEdit($db, $calendar, $a[0])){ 
				return true; 
			}
		}
		return false;
	}
}
