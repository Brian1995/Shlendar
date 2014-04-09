<?php

require_once 'lib/utils.php';

class XMLAttribute {
	
	private $name;
	private $value;
	
	function __construct($name, $value) {
		$this->name = $name;
		$this->value = $value;
	}
	
	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function getValue() {
		return $this->value;
	}

	public function setValue($value) {
		$this->value = $value;
	}
	
	public function __toString() {
		return $this->name.'="'.StringUtils::escapeHTML($this->value).'"';
	}

}
