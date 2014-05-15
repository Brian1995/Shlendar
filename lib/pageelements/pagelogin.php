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
		$this->submitUrl = $submitUrl;
	}
	
	public function toXML() {
		
		$login  = new XMLElement('div', 'id', 'login');
		$form = new XMLElement('form', 'id', 'login-form', 'action', $this->submitUrl, 'method', 'post');
		
		$divLeft  = new XMLElement('div', 'class', 'input');
		$divRight = new XMLElement('div', 'class', 'submit');
		
		$divUser   = new XMLElement('div', 'id', 'login-user');
		$divPass   = new XMLElement('div', 'id', 'login-pass');
		$divSubmit = new XMLElement('div', 'id', 'login-submit');
		
		$userLabel = new XMLElement('div');
		$userLabel->addChild(new XMLText('Benutzer'));
		$user = new XMLElement('input', 'type', 'text', 'name', 'username');
		
		$passwordLabel = new XMLElement('div');
		$passwordLabel->addChild(new XMLText("Passwort"));
		$password = new XMLElement('input', 'type', 'password', 'name', 'password');
		
		$submit = new XMLElement('input', 'type', 'submit');
		
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
			$form->addChild($divLeft);
				$divLeft->addChild($divUser);
					$divUser->addChild($userLabel);
					$divUser->addChild($user);
				$divLeft->addChild($divPass);
					$divPass->addChild($passwordLabel);
					$divPass->addChild($password);
			$form->addChild($divRight);
				$divRight->addChild($divSubmit);
					$divSubmit->addChild($submit);
		
		return $login;
	}
}
