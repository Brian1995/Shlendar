<?php

class PageButton extends PageElement {
	
	const STYLE_NONE   = NULL;
	const STYLE_SUBMIT = 'submit';
	const STYLE_EDIT   = 'edit';
	const STYLE_DELETE = 'delete';
	
	/** @var string */
	private $label;
	/** @var string */
	private $style;
	/** @var PageElement */
	private $icon;
	
	function __construct($label, $style=self::STYLE_NONE, $icon=NULL) {
		parent::__construct('button');
		$this->setProperty('type', 'submit');
		$this->setLabel($label);
		$this->setStyle($style);
		$this->setIcon($icon);
	}
	
	public function getLabel() {
		return $this->label;
	}

	public function setLabel($label) {
		$this->label = $label;
		return $this;
	}
		
	public function getStyle() {
		return $this->style;
	}

	public function setStyle($style=self::STYLE_NONE) {
		$this->style = $style;
		return $this;
	}
	
	public function getIcon() {
		return $this->icon;
	}

	public function setIcon($icon) {
		$this->icon = $icon;
		return $this;
	}
	
	public function toXML() {
		$element = parent::toXML();
		$label = $this->getLabel();
		$style = $this->getStyle();
		$icon = $this->getIcon();
		if ($style !== NULL) {
			$oldValue = $element->getAttribute('class');
			$element->setAttribute('class', $oldValue.' '.$style);
		}
		$element->addChild($span = new XMLElement('span'));
		if ($icon !== NULL) {
			$span->addChild($icon->toXML());
		}
		$span->addChild(new XMLText($label));
		return $element;
	}

}
