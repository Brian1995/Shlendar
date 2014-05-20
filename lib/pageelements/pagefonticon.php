<?php

class PageFontIcon extends PageElement {
	
	const NORMAL = NULL;
	const LARGER = 'fa-lg';
	const LARGER_2X = 'fa-2x';
	
	private $name;
	private $size;
	private $fixedWidth;
	
	function __construct($name, $size=NORMAL, $fixedWidth=FALSE) {
		parent::__construct('i');
		$this->name = $name;
		$this->size = $size;
		$this->fixedWidth = $fixedWidth;
	}
	
		
	public function toXML() {
		$element = parent::toXML();
		$class = 'fa fa-'.$this->name.($this->size === NULL ? '' : ' '.$this->size).($this->fixedWidth ? ' fa-fw' : '');
		$element->setAttribute('class', $class);
		return $element;
	}

}
