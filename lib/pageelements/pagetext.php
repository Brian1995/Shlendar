<?php

class PageText extends PageElement {
	
	private $text;
	
	function __construct($text) {
		parent::__construct();
		$this->text = $text;
	}
	
	public function toXML() {
		return new XMLText($this->text);
	}

}