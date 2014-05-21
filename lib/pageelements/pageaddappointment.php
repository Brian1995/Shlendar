<?php
/**
 * Description of pageaddappointment
 *
 * @author bussebr
 */
class PageAddAppointment extends PageContainer{
	
	private $submitURL;
	
	public function __construct($submitURL) {
		parent::__construct('div');
		$this->setProperty('id', 'add-appointment');
		$this->submitURL = $submitURL;
	}
	
	/**
	 * 
	 * @param DatabaseConnection $dbConnection
	 * @return boolean
	 */
	public static function addApppointment($dbConnection){
		var_dump("bla");
		if (filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_DEFAULT) === 'POST') {
			if (filter_has_var(INPUT_POST, 'title') && filter_has_var(INPUT_POST, 'fromDate')&& filter_has_var(INPUT_POST, 'toDate')&& filter_has_var(INPUT_POST, 'description')) {
				$title = filter_input(INPUT_POST, 'title');
				$from = filter_input(INPUT_POST, 'fromDate');
				$to = filter_input(INPUT_POST, 'toDate');
				$description = filter_input(INPUT_POST, 'description');
				$url = URL::urlFromCurrent();
				var_dump($url);
				$calendar = $url->getQueryParameter('calendar_id');
				$dbConnection->query("INSERT INTO appointments as a, a.calendar_id, a.from_date, a.to_date, a.title, a.description"
						. "VALUE ('%s', '%s', '%s', '%s', '%s');", $calendar, $from, $to, $title, $description);
				$url->setQueryParameter('action', 'listAppointments');
				$url->redirect();
			}
		}
		return false;
	}
	
	public function toXML(){
		$titleLabel = new XMLElement('div');
		$titleLabel->addChild(new XMLText("Titel"));
		$title = new XMLElement('input', 'type', 'text', 'name', 'title');
		
		$fromLabel = new XMLElement('div');
		$fromLabel->addChild(new XMLText("Von"));
		$from = new XMLElement('input', 'type', 'text', 'name', 'fromDate');
		
		$toLabel = new XMLElement('div');
		$toLabel->addChild(new XMLText("Bis"));
		$to = new XMLElement('input', 'type', 'text', 'name', 'toDate');
		
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
}
