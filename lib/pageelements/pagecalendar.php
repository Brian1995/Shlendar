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
		$currentDate  = $this->getCurrentDate();
		$viewDate     = $this->getViewDate();
		$firstOfMonth = $viewDate->copy()->setToFirstWeekdayOfMonth(Date::MONDAY);
		$topLeftDate  = $firstOfMonth->copy();
		if ($topLeftDate->getDay() != 1) {
			$topLeftDate->setToPreviousWeekday();
		}
		$day = $topLeftDate->copy();
		
		$calendar = new XMLElement('div', 'sidebar-calendar');
		
		$nav = new XMLElement('div', 'sidebar-calendar-header', 'table');
		$nav->addChild($navrow = new XMLElement('div', NULL, 'row'));
		$navrow->addChild($navPrevious = new XMLElement('div', NULL, 'cell previous'));
		$navrow->addChild($navTitle = new XMLElement('div', NULL, 'cell title'));
		$navrow->addChild($navNext = new XMLElement('div', NULL, 'cell next'));
		
		$urlPrevious = URL::urlFromCurrent();
		$urlPrevious->setQueryParameter('viewDate', $viewDate->copy()->addMonths(-1)->toDateString());
		$linkPrevious = new PageLink(new PageText('<<'), $urlPrevious);
		$navPrevious->addChild($linkPrevious->toXML());

		$urlNext = URL::urlFromCurrent();
		$urlNext->setQueryParameter('viewDate', $viewDate->copy()->addMonths(1)->toDateString());
		$linkNext = new PageLink(new PageText('>>'), $urlNext);
		$navNext->addChild($linkNext->toXML());
		
		$navTitle->addChild(new XMLText($viewDate->formatLocalized('%B %Y')));
			
		$table = new XMLElement('div', 'sidebar-calendar-entries', 'table');
		
		for ($y=0; $y<6; $y++) {
			$row = new XMLElement('div', NULL, 'row entryrow');
			$table->addChild($row);
			for ($x=0; $x<7; $x++) {
				$cell = new XMLElement('div', NULL, 'cell');
				$class = $cell->getAttribute('class');
				if ($day->isSameDay($currentDate)) {
					$class .= ' current';
				}
				if ($day->isSameMonth($firstOfMonth)) {
					$class .= ' current-month';
				}
				if ($day->isWeekend()) {
					$class .= ' weekend';
				}
				$cell->setAttribute('class', $class);
				$url = URL::urlFromCurrent();
				$url->setQueryParameter('viewDate', $day->toDateString());
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
