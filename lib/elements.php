<?php

require_once 'lib/element.php';
require_once 'lib/url.php';

class Link extends Element {
	
	private $content;
	private $url;
	
	public function __construct(Element $content=NULL, URL $url=NULL) {
		$this->content = $content;
		$this->url = $url;
	}

	public function getContent() {
		return $this->content;
	}

	public function setContent($content) {
		$this->content = $content;
	}

	public function getUrl() {
		return $this->url;
	}

	public function setUrl($url) {
		$this->url = $url;
	}

	public function toHTML() {
		$cs = NULL;
		$us = NULL;
		if (!is_null($this->content)) {
			$cs = $this->content->toHTML();
		}
	}
	
}
