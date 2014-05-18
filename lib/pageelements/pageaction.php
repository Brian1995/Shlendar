<?php

class PageAction extends PageElement {
	
	/** @var string */
	protected $action;
	/** @var string */
	protected $label;
	/** @var PageFontIcon|null */
	protected $icon = NULL;
	
	function __construct($action, $label, $icon = NULL) {
		parent::__construct('div');
		$this->setProperty('class', 'action');
		$this->setAction($action);
		$this->setLabel($label);
		$this->setIcon($icon);
	}
	
	public function getAction() {
		return $this->action;
	}

	public function setAction($action) {
		$this->action = $action;
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
		$actionURL = URL::urlFromRelativePath('index.php', URL::urlFromBase());
		$actionURL->setQuery(URL::urlFromCurrent()->getQuery());
		$actionURL->setQueryParameter('action', $this->getAction());
		$link = new XMLElement('a', 'href', $actionURL);
		$icon = $this->getIcon();
		if ($icon !== NULL) {
			$link->addChild($icon->toXML());
		}
		$labelElement = new PageText($this->getLabel());
		$link->addChild($labelElement->toXML());
		$element->addChild($link);
		return $element;
	}

}
