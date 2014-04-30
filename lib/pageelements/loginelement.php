<?php

require_once 'lib/xml.php';

/**
 * Description of loginelement
 *
 * @author bussebr
 */
class LoginElement extends PageElement {
	
	private $submitUrl;
	
	public function __construct($submitUrl) {
		$this->submitUrl = $submitUrl;
	}
	
	public function toXML() {
		$form = new XMLElement('form');
		$form->addAttribute('action', $this->submitUrl);
		$form->addAttribute('method', 'post');
		$user = new XMLElement('input');
		$user->addAttribute('type', 'text');
		$user->addAttribute('name', 'username');
		$password = new XMLElement('input');
		$password->addAttribute('type', 'password');
		$password->addAttribute('name', 'password');
		$submit = new XMLElement('input');
		$submit->addAttribute('type', 'submit');
		$userLabel = new XMLElement('div');
		$userLabel->addChild(new XMLText('Benutzer'));
		$passwordLabel = new XMLElement('div');
		$passwordLabel->addChild(new XMLText("Passwort"));
		
		
		$form->addChild($userLabel);
		$form->addChild($user);
		$form->addChild($passwordLabel);
		$form->addChild($password);
		$form->addChild($submit);
		return $form;
	}
}
