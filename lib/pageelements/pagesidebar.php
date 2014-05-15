<?php

require_once 'lib/utils.php';

class PageSidebar extends PageContainer {
	
	/**
	 * @var string
	 */
	private $name;
	
	/**
	 * @param string $name
	 */
	function __construct($name) {
		parent::__construct();
		$this->name = $name;
	}
	
	public function toXML() {
		$sidebar = new XMLElement('div', 'sidebar-'.$this->name, 'sidebar blured');
		$sidebar->addChild($headline = new XMLElement('h2'));
		$headline->addChild(new XMLText('Navigation'));
		for ($index = 0; $index < $this->getChildCount(); $index++) {
			$sidebar->addChild($container = new XMLElement('div', NULL, 'sidebar-container'));
			$content = $this->getChild($index);
			$xml = $content->toXML();
			$container->addChild($xml);
		}
		return $sidebar;
	}

	
}
