<?php

class PageGroupEditor extends PageElement {
	
	private $db;
	
	function __construct(DatabaseConnection $db) {
		parent::__construct('div');
		$this->setProperty('id', 'group-edit');
		$this->db = $db;
	}
	
	public function toXML() {
		$element = parent::toXML();
		return $element;
	}

}
