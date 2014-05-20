<?php

class PageGroupManagement extends PageElement {
	
	function __construct() {
		parent::__construct('div');
		$this->setProperties('id', 'group-management');
	}
	
	public function toXML() {
		$element = parent::toXML();
		return $element;
	}

}
