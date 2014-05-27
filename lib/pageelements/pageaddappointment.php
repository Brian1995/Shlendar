<?php

/**
 * Description of pageaddappointment
 *
 * @author bussebr
 */
class PageAddAppointment extends PageContainer {

    private $submitURL;

    public function __construct($submitURL) {
        parent::__construct('div');
        $this->setProperty('id', 'add-appointment');
        $this->submitURL = $submitURL;
    }

    public function toXML() {
        $titleLabel = new XMLElement('div');
        $titleLabel->addChild(new XMLText("Titel"));
        $title = new XMLElement('input', 'type', 'text', 'name', 'title');

        $fromLabel = new XMLElement('div');
        $fromLabel->addChild(new XMLText("Von"));
        $from = new XMLElement('input', 'type', 'text', 'name', 'fromDate', 'id', 'datetimepicker-from');

        $toLabel = new XMLElement('div');
        $toLabel->addChild(new XMLText("Bis"));
        $to = new XMLElement('input', 'type', 'text', 'name', 'toDate', 'id', 'datetimepicker-to');

        $descriptionLabel = new XMLElement('div');
        $descriptionLabel->addChild(new XMLText("Beschreibung"));
        $description = new XMLElement('input', 'type', 'text', 'name', 'description');

        $divTitle = new XMLElement('div', 'id', 'add-appointment-title');
        $divTitle->addChild($titleLabel);
        $divTitle->addChild($title);

        $divFrom = new XMLElement('div');
        $divFrom->addChild($fromLabel);
        $divFrom->addChild($from);

        $divTo = new XMLElement('div');
        $divTo->addChild($toLabel);
        $divTo->addChild($to);

        $divDescription = new XMLElement('div');
        $divDescription->addChild($descriptionLabel);
        $divDescription->addChild($description);

        $submitText = new PageText("hintufügen");

        $submit = new XMLElement('button', 'type', 'submit', 'name', 'submit-button', 'value', 'val');
        $submit->addChild($submitText->toXML());

        $form = new XMLElement('form', 'id', 'add-appointment', 'action', $this->submitURL, 'method', 'post');
        $form->addChild($divTitle);
        $form->addChild($divFrom);
        $form->addChild($divTo);
        $form->addChild($divDescription);
        $form->addChild($submit);

        $addAppointment = parent::toXML();
        $addAppointment->addChild($form);

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
				var_dump($calendar);
                $result = $dbConnection->query(
                        "INSERT INTO appointments (calendar_id, start_date, end_date, title, description)
					 VALUE ('%s', '%s', '%s', '%s', '%s');"
                        , $calendar, $from, $to, $title, $description);
                if ($result) {
                    $url = URL::createStatic();
                    $url->setDynamicQueryParameter('action', 'listAppointments');
                    $url->redirect();
                } else {
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
		var_dump(mysql_num_rows($result));
		while ($a = mysql_fetch_array($result)){
			if(PageCalendarEditor::groupCanEdit($db, $calendar, $a[0])){ 
				return true; 
			}
		}
		return false;
	}
}
