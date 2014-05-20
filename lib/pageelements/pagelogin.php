<?php

require_once 'lib/session.php';

/**
 * Description of loginelement
 *
 * @author bussebr
 */
class PageLogin extends PageElement {
	
	private $submitUrl;
	
	public function __construct($submitUrl) {
		parent::__construct('div');
		$this->setProperty('id', 'login');
		$this->submitUrl = $submitUrl;
	}
	
	public function toXML() {
		
		$login = parent::toXML();
		$form = new XMLElement('form', 'id', 'login-form', 'action', $this->submitUrl, 'method', 'post');
				
		$divUser   = new XMLElement('div', 'id', 'login-user');
		$divPass   = new XMLElement('div', 'id', 'login-pass');
		
		$userLabel = new XMLElement('div');
		$userLabel->addChild(new XMLText('Benutzer'));
		$user = new XMLElement('input', 'type', 'text', 'name', 'username');
		
		$passwordLabel = new XMLElement('div');
		$passwordLabel->addChild(new XMLText("Passwort"));
		$password = new XMLElement('input', 'type', 'password', 'name', 'password');
		
		$submitIcon = new PageFontIcon('sign-in', PageFontIcon::NORMAL, TRUE);
		$submitText = new PageText('Anmelden');
		
		$submit = new XMLElement('button', 'type', 'submit', 'name' , 'submit-button', 'value', 'val');
		$submit->addChild($submitIcon->toXML());
		$submit->addChild($submitText->toXML());
		
		$login->addChild($loginHeader = new XMLElement('h2'));
		$loginHeader->addChild(new XMLText('Login'));
		
		if (Session::loginFailed()) {
			$failed = new XMLElement('div', 'id', 'login-failed');
			$failedText1 = new XMLElement('p', 'id', 'login-failed-text1');
			$failedText1->addChild(new XMLText('Fehler beim letzten Anmeldeversuch!'));
			$failedText2 = new XMLElement('p', 'id', 'login-failed-text2');
			$failedText2->addChild(new XMLText("Bitte überprüfen sie ihren Benutzernamen und das Passwort und versuchen sie es erneut."));
			
			$login->addChild($failed);
				$failed->addChild($failedText1);
				$failed->addChild($failedText2);
		}
		
		$login->addChild($form);
			$form->addChild($divUser);
				$divUser->addChild($userLabel);
				$divUser->addChild($user);
			$form->addChild($divPass);
				$divPass->addChild($passwordLabel);
				$divPass->addChild($password);
			$form->addChild($submit);
		
		return $login;
	}
}
