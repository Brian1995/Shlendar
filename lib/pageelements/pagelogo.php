<?php

/**
 * This element represents a logo element. It 
 */
class PageLogo extends PageElement {
	
	/** @var string|null Text displayed as part of the logo. */
	private $logoText;
	
	/** @var URL|null Optional link of the logo. */
	private $linkUrl;
	
	/**
	 * 
	 * @param string|null $logoText
	 * @param URL|null $linkUrl
	 */
	function __construct($logoText=NULL, $linkUrl=NULL) {
		parent::__construct('div');
		$this->setProperty('class', 'logo');
		$this->setLogoText($logoText);
		$this->setLinkUrl($linkUrl);
	}
	
	/**
	 * 
	 * @return string|null
	 */
	public function getLogoText() {
		return $this->logoText;
	}

	/**
	 * 
	 * @param string|null $logoText
	 * @return PageLogo
	 */
	public function setLogoText($logoText) {
		$this->logoText = $logoText;
		return $this;
	}

	/**
	 * 
	 * @return URL|null
	 */
	public function getLinkUrl() {
		return $this->linkUrl;
	}

	/**
	 * 
	 * @param URL|null $linkUrl
	 * @return PageLogo
	 */
	public function setLinkUrl($linkUrl) {
		$this->linkUrl = $linkUrl;
		return $this;
	}

	/**
	 * 
	 * @return XMLElement
	 */
	public function toXML() {
		$container = parent::toXML();
		if ($this->linkUrl === NULL) {
			$parent = $container;
		} else {
			$container->addChild($parent = new XMLElement('a', 'class', 'link', 'href', $this->linkUrl));
		}
		$parent->addChild($icon = new XMLElement('div', 'class', 'icon'));
		if ($this->logoText !== NULL) {
			$parent->addChild($text = new XMLElement('div', 'class', 'text'));
			$text->addChild(new XMLText($this->logoText));
		}
		return $container;
	}

}
