<?php

class PageGroupManagement extends PageElement {
	
	/** @var DatabaseConnection */
	private $db;
	
	function __construct(DatabaseConnection $db) {
		parent::__construct('div');
		$this->setProperties('id', 'group-management');
		$this->db = $db;
	}
	
	public function toXML() {
		$element = parent::toXML();
		
		$element->addChild($headline = new XMLElement('h1'));
		$headline->addChild(new XMLText('Gruppen verwalten'));
		//$db->query("SELECT ")
		
		return $element;
	}

}
