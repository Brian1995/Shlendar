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
	
	/**
	 * 
	 * @param string $headerType
	 * @param PageText|string|null $text
	 */
	function __construct($headerType, $text=NULL) {
		parent::__construct($headerType);
		$this->setText($text);
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
	 * @return XMLElement
	 */
	public function toXML() {
		$element = parent::toXML();
		$text = $this->getText();
		if ($text !== NULL) {
			$element->addChild($text->toXML());
		}
		return $element;
	}
	
}
