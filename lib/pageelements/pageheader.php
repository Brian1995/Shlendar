<?php

require_once 'lib/xml.php';

class PageHeader extends PageElement {
	
	/** @var PageImage */
	private $logo;
	
	/** @var PageElement */
	private $title;
	
	/**
	 * 
	 * @return PageImage|null
	 */
	public function getLogo() {
		return $this->logo;
	}

	/**
	 * 
	 * @param PageImage|null $logo
	 * @return \PageHeader
	 */
	public function setLogo($logo) {
		$this->logo = $logo;
		return $this;
	}

	/**
	 * 
	 * @return PageElement|null
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * 
	 * @param PageElement|null $siteTitle
	 * @return \PageHeader
	 */
	public function setTitle($siteTitle) {
		$this->title = $siteTitle;
		return $this;
	}
	
	public function toXML() {
		$header = new XMLElement('header');
		$header->addAttribute('id', 'site-header');
		$header->addAttribute('class', 'table blured');
		$header->addChild($headerContainer = new XMLElement('div'));
		$headerContainer->addAttribute('class', 'row');

		$left = new XMLElement('div');
		$left->addAttribute('class', 'cell left');
		$headerContainer->addChild($left);

		$center = new XMLElement('div');
		$center->addAttribute('class', 'cell center');
		$headerContainer->addChild($center);
		
		$right = new XMLElement('div');
		$right->addAttribute('class', 'cell right');
		$headerContainer->addChild($right);
		
		if ($this->getLogo() !== NULL) {
			$left->addChild($this->getLogo()->toXML());
		}
		if ($this->getTitle() !== NULL) {
			$h1 = new XMLElement('h1');
			$center->addChild($h1);
			$h1->addChild($this->getTitle()->toXML());
		}
		if (!Session::isLoggedIn()) {
			$login = new PageLink("Login", URL::urlFromCurrent()->setQueryParameter("action", "execLogin"));
		} else {
			$login = new PageLink("Logout", URL::urlFromCurrent()->setQueryParameter("action", "logout"));
		}
		$right->addChild($login->toXML());
		
		return $header;
	}
	
}
