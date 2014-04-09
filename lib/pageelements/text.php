<?php

require_once 'lib/xml.php';

class Text extends PageElement {
	
	private $text;
	
	function __construct($text) {
		$this->text = $text;
	}
	
	public function toXML() {
		return new XMLText($this->text);
	}

}