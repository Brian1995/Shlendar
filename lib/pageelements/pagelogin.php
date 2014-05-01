<?php

require_once 'lib/session.php';
require_once 'lib/xml.php';

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
		
		$login  = new XMLElement('div', 'login', 'blured');
		$form = new XMLElement('form', 'login-form');
		$form->addAttribute('action', $this->submitUrl)->addAttribute('method', 'post');
		
		$divTable = new XMLElement('div', 'login-table', 'table');
		$divRow   = new XMLElement('div', 'login-row',   'row');
		$divLeft  = new XMLElement('div', 'login-left',  'cell left');
		$divRight = new XMLElement('div', 'login-right', 'cell right');
		
		$divUser   = new XMLElement('div', 'login-user');
		$divPass   = new XMLElement('div', 'login-pass');
		$divSubmit = new XMLElement('div', 'login-submit');
		
		$userLabel = new XMLElement('div');
		$userLabel->addChild(new XMLText('Benutzer'));
		$user = new XMLElement('input');
		$user->addAttribute('type', 'text')->addAttribute('name', 'username');
		
		$passwordLabel = new XMLElement('div');
		$passwordLabel->addChild(new XMLText("Passwort"));
		$password = new XMLElement('input');
		$password->addAttribute('type', 'password')->addAttribute('name', 'password');
		
		$submit = new XMLElement('input');
		$submit->addAttribute('type', 'submit');
		
		if (Session::loginFailed()) {
			$failed = new XMLElement('div', 'login-failed');
			$failedText1 = new XMLElement('p', 'login-failed-text1');
			$failedText1->addChild(new XMLText('Fehler beim letzten Anmeldeversuch!'));
			$failedText2 = new XMLElement('p', 'login-failed-text2');
			$failedText2->addChild(new XMLText("Bitte überprüfen sie ihren Benutzernamen und das Passwort und versuchen sie es erneut."));
			
			$login->addChild($failed);
				$failed->addChild($failedText1);
				$failed->addChild($failedText2);
		}
		
		$login->addChild($form);
			$form->addChild($divTable);
				$divTable->addChild($divRow);
					$divRow->addChild($divLeft);
						$divLeft->addChild($divUser);
							$divUser->addChild($userLabel);
							$divUser->addChild($user);
						$divLeft->addChild($divPass);
							$divPass->addChild($passwordLabel);
							$divPass->addChild($password);
					$divRow->addChild($divRight);
						$divRight->addChild($divSubmit);
							$divSubmit->addChild($submit);
		
		return $login;
	}
}
