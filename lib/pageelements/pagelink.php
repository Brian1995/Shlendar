<?php

require_once 'lib/utils.php';

class PageLink extends PageElement {
	
	/**
	 *
	 * @var PageElement
	 */
	private $content;
	
	public function __construct($content=NULL, $url=NULL) {
		parent::__construct('a');
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
		$a = parent::toXML();
		if ($this->content !== NULL) {
			$content = $this->content->toXML();
			if ($content instanceof XMLElement) {
				$a->addChild($content);
			} else {
				$span = new XMLElement('span');
				$span->addChild($content);
				$a->addChild($span);				
			}
		}
		return $a;
	}
	
}