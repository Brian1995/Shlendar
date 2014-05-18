<?php

class PageAction extends PageElement {
	
	function __construct() {
		parent::__construct('div');
		$this->setProperty('class', 'action');
	}

}
