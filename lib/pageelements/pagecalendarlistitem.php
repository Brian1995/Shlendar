<?php

class PageCalendarListItem {

	private $id = NULL;
	private $name = NULL;

	function __construct($id, $name) {
		$this->id = $id;
		$this->name = $name;
	}

	public function toXML(){
		
		$url = URL::createStatic();
		$url->setDynamicQueryParameter('action', 'listAppointments');
		$url->setDynamicQueryParameter('calendar', $this->id);

		$text = new PageTextContainer('span', $this->name);
		$link = new PageLink($text, $url);
		
		$listItem = new PageContainer('div', 'class', 'action calendar-item');
		$listItem->addChild($link);
		
		return $listItem->toXML();
	}
}
