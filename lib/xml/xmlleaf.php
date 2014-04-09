<?php

require_once 'lib/xml/xmlnode.php';

abstract class XMLLeaf {
	
	/**
	 *
	 * @var XMLNode
	 */
	private $parent = NULL;

	/**
	 * 
	 * @return XMLNode
	 */
	public function getParent() {
		return $this->parent;
	}

	/**
	 * @param XMLNode $parent
	 */
	protected function setParent($parent) {
		$this->parent = $parent;
	}
	
}
