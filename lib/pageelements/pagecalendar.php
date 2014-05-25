<?php

require_once 'lib/utils.php';

class PageCalendar extends PageElement {
	
	/**
	 *@var Date
	 */
	private $currentDate = NULL;
	
	/**
	 * @var Date
	 */
	private $viewDate = NULL;
	
	function __construct() {
		parent::__construct('div');
		$this->setProperty('id', 'sidebar-calendar');
	}

	
	/**
	 * 
	 * @return Date
	 */
	public function getCurrentDate() {
		return $this->currentDate === NULL ? Date::now() : $this->currentDate;
	}

	/**
	 * 
	 * @param Date|null $currentDate
	 * @return PageCalendar
	 */
	public function setCurrentDate($currentDate) {
		$this->currentDate = $currentDate;
		return $this;
	}

	/**
	 * 
	 * @return Date
	 */
	public function getViewDate() {
		return $this->viewDate === NULL ? $this->getCurrentDate() : $this->viewDate;
	}

	/**
	 * 
	 * @param Date|null $viewDate
	 * @return PageCalendar
	 */
	public function setViewDate($viewDate) {
		$this->viewDate = $viewDate;
		return $this;
	}
	
	public function toXML() {
		$calendar = parent::toXML();
		
		$currentDate  = $this->getCurrentDate();
		$viewDate     = $this->getViewDate();
		$firstOfMonth = $viewDate->copy()->setToFirstWeekdayOfMonth(Date::MONDAY);
		$topLeftDate  = $firstOfMonth->copy();
		if ($topLeftDate->getDay() != 1) {
			$topLeftDate->setToPreviousWeekday();
		}
		$day = $topLeftDate->copy();
		
		$nav = new XMLElement('div', 'id', 'sidebar-calendar-header');
		$nav->addChild($navPrevious = new XMLElement('div', 'class', 'previous'));
		$nav->addChild($navTitle    = new XMLElement('div', 'class', 'title'));
		$nav->addChild($navNext     = new XMLElement('div', 'class', 'next'));
		
		$urlPrevious = URL::createCurrent();
		$urlPrevious->setStaticQueryParameter('viewDate', $viewDate->copy()->addMonths(-1)->toDateString());
		$linkPrevious = new PageLink(new PageFontIcon('chevron-left', PageFontIcon::NORMAL, TRUE), $urlPrevious);
		$navPrevious->addChild($linkPrevious->toXML());

		$urlNext = URL::createCurrent();
		$urlNext->setStaticQueryParameter('viewDate', $viewDate->copy()->addMonths(1)->toDateString());
		$linkNext = new PageLink(new PageFontIcon('chevron-right', PageFontIcon::NORMAL, TRUE), $urlNext);
		$navNext->addChild($linkNext->toXML());
		
		$navTitle->addChild($navTitleH3 = new XMLElement('h3'));
		$navTitleH3->addChild(new XMLText($viewDate->formatLocalized('%B %Y')));
			
		$table = new XMLElement('div', 'id', 'sidebar-calendar-entries');
		
		// add weekday header row
		$row = new XMLElement('div', 'class', 'dayrow');
		$table->addChild($row);
		for ($x=0; $x<7; $x++) {
			$cell = new XMLElement('div', 'class', 'day day'.$x);
			$cell->addChild(new XMLText($day->copy()->addDays($x)->toShortDayName()));
			$row->addChild($cell);
		}
		
		for ($y=0; $y<6; $y++) {
			$row = new XMLElement('div', 'class', 'entryrow');
			$table->addChild($row);
			for ($x=0; $x<7; $x++) {
				$cell = new XMLElement('div', 'class', 'cell');
				$class = $cell->getAttribute('class');
				$class .= ' day'.$x;
				if ($day->isSameDay($currentDate)) {
					$class .= ' current';
				}
				if ($day->isSameDay($viewDate)) {
					$class .= ' selected';
				}
				if ($day->isSameMonth($firstOfMonth)) {
					$class .= ' current-month';
				}
				if ($day->isWeekend()) {
					$class .= ' weekend';
				}
				$cell->setAttribute('class', $class);
				$url = URL::createCurrent();
				$url->setStaticQueryParameter('viewDate', $day->toDateString());
				$link = new PageLink(new PageText($day->formatLocalized('%e')), $url);
				$cell->addChild($link->toXML());
				$day->addDays(1);
				$row->addChild($cell);
			}
		}
		$calendarHeadline = new XMLElement('h2');
		$calendarHeadline->addChild(new XMLText('Kalender'));
		$calendar->addChild($calendarHeadline);
		$calendar->addChild($nav);
		$calendar->addChild($table);
		return $calendar;
	}

}
