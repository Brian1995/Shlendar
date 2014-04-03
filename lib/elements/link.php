<?php

class Link extends Element {
	
	/**
	 *
	 * @var Element
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
		return $this->getAttribute('href');
	}

	/**
	 * 
	 * @param URL $url
	 * @return URL 
	 */
	public function setHref($url=NULL) {
		return $this->setAttribute('href', $url);
	}

	/**
	 * @return string 
	 */
	public function toHTML() {
		$content = NULL;
		if (!is_null($this->content)) {
			$content = $this->content->toHTML();
		}
		$s = '<a';
		$attributes = $this->getAttributes();
		if (count($attributes) > 0) {
			$s .= ' ';
			$s .= StringUtils::asAttributeString($attributes);
		}
		if (is_null($content)) {
			$s .= '/>';
		} else {
			$s .= '>'.$content.'</a>';
		}
		return $s;
	}
	
}