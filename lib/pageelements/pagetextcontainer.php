<?php

class PageTextContainer extends PageElement {
	
	const H1 = 'h1';
	const H2 = 'h2';
	const H3 = 'h3';
	const H4 = 'h4';
	const H5 = 'h5';
	const H6 = 'h6';
	const P  = 'p';
	
	/** @var PageText */
	private $text;
	
	/** @var PageElement */
	private $icon;
	
	/**
	 * 
	 * @param string $headerType
	 * @param PageText|string|null $text
	 * @param PageElement|null $icon
	 */
	function __construct($headerType, $text=NULL, $icon=NULL) {
		parent::__construct($headerType);
		$this->setText($text);
		$this->setIcon($icon);
	}
	
	/**
	 * @return PageText|null
	 */
	public function getText() {
		return $this->text;
	}

	/**
	 * 
	 * @param PageText|string|null $text
	 * @return \PageHeader
	 */
	public function setText($text) {
		$this->text = ($text === NULL || $text instanceof PageText) ? $text : new PageText($text);
		return $this;
	}
	
	/**
	 * 
	 * @return PageElement|null
	 */
	public function getIcon() {
		return $this->icon;
	}

	/**
	 * 
	 * @param PageElement|null $icon
	 * @return PageTextContainer
	 */
	public function setIcon($icon) {
		$this->icon = $icon;
		return $this;
	}

		
	/**
	 * 
	 * @return XMLElement
	 */
	public function toXML() {
		$element = parent::toXML();
		$icon = $this->getIcon();
		$text = $this->getText();
		if ($icon !== NULL) {
			$element->addChild($icon->toXML());
		}
		if ($text !== NULL) {
			$element->addChild($text->toXML());
		}
		return $element;
	}
	
}
