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
		$loginElement = new XMLElement('div');
		$loginHeader = new PageTextContainer(PageTextContainer::H2, 'Anmeldedaten');
		
		$form = new XMLElement('form', 'class', 'login-form', 'action', $this->submitUrl, 'method', 'post');
		
		$divUserPass = new XMLElement('div', 'class', 'login-userpass');
		$divUser   = new XMLElement('div', 'class', 'login-user');
		$divPass   = new XMLElement('div', 'class', 'login-pass');
		
		$userLabel = new XMLElement('div');
		$userLabel->addChild(new XMLText('Benutzer'));
		$user = new XMLElement('input', 'type', 'text', 'name', 'username', 'maxlength', '255');
		
		$passwordLabel = new XMLElement('div');
		$passwordLabel->addChild(new XMLText("Passwort"));
		$password = new XMLElement('input', 'type', 'password', 'name', 'password', 'maxlength', '255');
		
		$submit = new PageButton('Anmelden', PageButton::STYLE_SUBMIT, PageFontIcon::create('sign-in', PageFontIcon::NORMAL, TRUE));
		
		$submitContainer = new XMLElement('div', 'class', 'login-button');
				
		$failed = NULL;
		if (Session::loginFailed()) {
			$failed = new XMLElement('div', 'class', 'login-failed');
			$failedText1 = new PageTextContainer(PageTextContainer::P, 'Fehler beim letzten Anmeldeversuch!');
			$failedText2 = new PageTextContainer(PageTextContainer::P, 'Bitte überprüfen sie ihren Benutzernamen und das Passwort und versuchen sie es erneut.');
			$failed->addChild($failedText1->toXML());
			$failed->addChild($failedText2->toXML());
		}
		
		$login->addChild($loginElement);
			$loginElement->addChild($loginHeader->toXML());
			if ($failed) { $loginElement->addChild($failed); }
			$loginElement->addChild($form);
				$form->addChild($divUserPass);
					$divUserPass->addChild($divUser);
						$divUser->addChild($userLabel);
						$divUser->addChild($user);
					$divUserPass->addChild($divPass);
						$divPass->addChild($passwordLabel);
						$divPass->addChild($password);
				$form->addChild($submitContainer);
					$submitContainer->addChild($submit->toXML());
		
		return $login;
	}
}
