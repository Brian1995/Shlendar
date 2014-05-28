<?php

/**
 * Description of pageappointmentlist
 *
 * @author Brian
 */
class PageAppointmentList extends PageContainer {

	const OUTPUT_FORMAT = '%a, %d. %B %Y';
	/**
	 * @var DatabaseConnection
	 */
	private $dbConnection;
	private $calendar_id;
	private $editable;

	function __construct($dbConnection, $calendar_id, $editable) {
		parent::__construct('div');
		$this->setProperty('id', 'appointment-list');
		
		$this->dbConnection = $dbConnection;
		$this->calendar_id = $calendar_id;
		$this->editable = $editable;
	}
	
	
	
	public function toXML() {
		$url = URL::createCurrent();
		$viewDateString = $url->getStaticQueryParameter('viewDate');
		if ($viewDateString === NULL) {
			$viewDate = Date::now();
		} else {
			$viewDate = Date::createFromFormat('Y-m-d', $viewDateString);
		}
		
		$daysBefore = $url->getStaticQueryParameter('date-soff');
		if ($daysBefore === NULL) {
			$daysBefore = 5;
		}
		$daysAfter = $url->getStaticQueryParameter('date-eoff');
		if ($daysAfter === NULL) {
			$daysAfter = 5;
		}
		
		$minDate = Date::instance($viewDate)->addDays(-$daysBefore)->setToStartOfDay();
		$maxDate = Date::instance($viewDate)->addDays(+$daysAfter)->setToEndOfDay();
		
		$result = $this->dbConnection->query(
			"SELECT * 
			 FROM appointments AS a
			 WHERE a.calendar_id = '%s'
				AND a.start_date >= '%s'
				AND a.end_date <= '%s'
			 ORDER BY a.start_date;", $this->calendar_id, $minDate, $maxDate);
		

		$element = parent::toXML();
		$container = new PageContainer('div');
		$container->addChild($header = new PageTextContainer(PageTextContainer::H2, 'Termine'));
		$infoString = 'Termine vom '.$minDate->formatLocalized(self::OUTPUT_FORMAT).' bis zum '.$maxDate->formatLocalized(self::OUTPUT_FORMAT).':';
		$container->addChild($info = new PageTextContainer(PageTextContainer::P, $infoString));
		$info->setProperties('style','font-size: 0.8em; margin-top:-1.0em; border-bottom: 1px solid #95a5a6; margin-bottom:0.5em;');
		$container->addChild($list = new PageContainer('div'));
		
		while ($a = mysql_fetch_row($result)) {
			$item = new PageAppointmentListItem($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $this->editable);
			$list->addChild($item);
		}
		$element->addChild($container->toXML());
		return $element;
	}

}
