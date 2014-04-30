<?php

require_once 'lib/pageelements.php';
require_once 'lib/xml.php';

/**
 * Description of loginelement
 *
 * @author bussebr
 */
class LoginElement extends PageElement {
	
	public function __construct() {
		
	}
	
	public function toXML() {
		$form = new XMLElement('form');
		return $form;
	}
}
