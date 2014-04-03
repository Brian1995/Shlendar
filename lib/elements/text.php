<?php

class Text extends Element {
	
	private $text;
	
	function __construct($text) {
		$this->text = is_null($text) ? '' : StringUtils::escapeHTML($text);
	}
	
	public function toHTML() {
		return $this->text;
	}

}