<?php

require_once 'lib/utils.php';
require_once 'lib/xml.php';

class Link extends PageElement {
	
	/**
	 *
	 * @var PageElement
	 */
	private $content;
	
	public function __construct($content=NULL, $url=NULL) {
		$this->setContent($content);
		$this->setHref($url);
	}

	/**
	 * 
	 * @return Element
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * 
	 * @param Element $content
	 */
	public function setContent($content=NULL) {
		$this->content = $content;
	}
	
	/**
	 * 
	 * @return URL
	 */
	public function getHref() {
		return $this->getProperty('href');
	}

	/**
	 * 
	 * @param URL $url
	 * @return URL 
	 */
	public function setHref($url=NULL) {
		return $this->setProperty('href', $url);
	}

	/**
	 * @return XMLElement 
	 */
	public function toXML() {
		$a = new XMLElement('a');
		$properties = $this->getProperties();
		foreach ($properties as $name => $value) {
			$a->addAttribute($name, $value);
		}
		if (!is_null($this->content)) {
			$content = $this->content->toXML();
			$a->addChild($content);
		}
		return $a;
	}
	
}