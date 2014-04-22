<?php

class PageImage extends PageElement {
	
	private $imageURL;
	private $link;
	
	function __construct($imageURL, $link=NULL) {
		$this->imageURL = $imageURL;
		$this->link = $link;
	}
	
	public function getImageURL() {
		return $this->imageURL;
	}

	public function setImageURL($imageURL) {
		$this->imageURL = $imageURL;
		return $this;
	}

	public function getLink() {
		return $this->link;
	}

	public function setLink($link) {
		$this->link = $link;
		return $this;
	}
	
	public function toXML() {
		if ($this->getImageURL() !== NULL) {
			$img = new XMLElement('img');
			$img->setAttribute('src', $this->getImageURL());
			if ($this->getLink() !== NULL) {
				$a = new XMLElement('a');
				$a->setAttribute('href', $this->getLink());
				$a->addChild($img);
				return $a;
			} 
			return $img;
		}
		return NULL;
	}
}
