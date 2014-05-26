<?php

class PageAction extends PageElement {
	
	/** @var URL */
	protected $actionUrl;
	/** @var string */
	protected $label;
	/** @var PageFontIcon|null */
	protected $icon = NULL;
	
	function __construct($actionUrl, $label, $icon = NULL) {
		parent::__construct('div');
		$this->setProperty('class', 'action');
		$this->setActionUrl($actionUrl);
		$this->setLabel($label);
		$this->setIcon($icon);
	}
	
	public function getActionUrl() {
		return $this->actionUrl;
	}

	public function setActionUrl($actionUrl) {
		$this->actionUrl = $actionUrl;
		return $this;
	}
	
	public function getLabel() {
		return $this->label;
	}
	
	public function setLabel($label) {
		$this->label = $label;
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

		$span = new PageTextContainer('span', $this->getLabel(), $this->getIcon());
		$link = new PageLink($span, $this->getActionUrl());
		
		$element->addChild($link->toXML());
		return $element;
	}

}
