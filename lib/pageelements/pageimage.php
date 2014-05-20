<?php

class PageImage extends PageElement {
	
	/**
	 * 
	 * @param URL $imageURL
	 * @param URL $link
	 */
	function __construct($imageURL, $link=NULL) {
		parent::__construct('img');
		$this->setImageURL($imageURL);
		$this->setLink($link);
	}
	
	/**
	 * 
	 * @return URL|null
	 */
	public function getImageURL() {
		return $this->getProperty('src');
	}

	/**
	 * 
	 * @param URL|null $imageURL
	 * @return \PageImage
	 */
	public function setImageURL($imageURL) {
		$this->setProperty('src', $imageURL);
		return $this;
	}

	/**
	 * 
	 * @return URL|null
	 */
	public function getLink() {
		return $this->getProperty('href');
	}
	
	/**
	 * 
	 * @param URL|null $link
	 * @return \PageImage
	 */
	public function setLink($link) {
		$this->setProperty('href', $link);
		return $this;
	}
	
	protected function getExcludeList() {
		return array('href');
	}

	public function toXML() {
		$img = parent::toXML();
		if ($this->getLink() !== NULL) {
			$a = new XMLElement('a', 'href', $this->getLink());
			$a->addChild($img);
			return $a;
		} 
		return $img;
	}
}
