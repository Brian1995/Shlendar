<?php

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
		$header = new XMLElement('header', 'id', 'site-header');

		$left   = new XMLElement('div', 'class', 'logo');
		$center = new XMLElement('div', 'class', 'title');
		$right = new XMLElement('div', 'class', 'login');
		
		$header->addChild($left);
		$header->addChild($center);
		$header->addChild($right);
		
		if ($this->getLogo() !== NULL) {
			$left->addChild($this->getLogo()->toXML());
		}
		if ($this->getTitle() !== NULL) {
			$h1 = new XMLElement('h1');
			$center->addChild($h1);
			$h1->addChild($this->getTitle()->toXML());
		}
		$url = URL::urlFromCurrent();
		$url->setPathRelativeToCurrentPath('index.php', $url);
		if (!Session::isLoggedIn()) {
			$url->setQueryParameter("action", "login");
			$login = new PageLink(new PageText("Anmelden"), $url);
		} else {
			$url->setQueryParameter("action", "logout");
			$login = new PageLink(new PageText("Abmelden"), $url);
		}
		$right->addChild($login->toXML());
		
		return $header;
	}
	
}
