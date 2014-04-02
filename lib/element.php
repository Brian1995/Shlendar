<?php

require_once 'lib/arraylist.php';

abstract class Element {
	
	abstract function toHTML();
	
	public function __toString() {
		return $this->toHTML();
	}
	
}

abstract class ElementContainer extends Element {
	
	private $children;
	
	public function __construct() {
		$this->children = new ArrayList();
	}
	
	public function addChild(Element $element, $index=-1) {
		$this->children->add($element, $index);
	}
	
	public function removeChild($index) {
		return $this->children->remove($index);
	}
	
}
