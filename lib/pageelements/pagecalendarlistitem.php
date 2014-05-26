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
		
		$name = $this->name;
		if (strlen($name) > 20) {
			$name= substr($name, 0, 17) . '...';
		}

		$text = new PageTextContainer('span', $name, PageFontIcon::create('calendar', PageFontIcon::NORMAL, TRUE));
		$link = new PageLink($text, $url);
		
		$listItem = new PageContainer('div', 'class', 'action calendar-item');
		$listItem->addChild($link);
		
		return $listItem->toXML();
	}
}
