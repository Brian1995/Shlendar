<?php

require_once 'lib/xml.php';

class PageSplit extends PageElement {
	
	/** @var PageElement|null */
	private $left;
	/** @var PageElement|null */
	private $center;
	/** @var PageElement|null */
	private $right;
	
	/**
	 * 
	 * @return PageElement|null
	 */
	public function getLeft() {
		return $this->left;
	}

	/**
	 * 
	 * @param PageElement|null $left
	 * @return \PageSplit
	 */
	public function setLeft($left) {
		$this->left = $left;
		return $this;
	}

	/**
	 * 
	 * @return PageElement|null
	 */
	public function getCenter() {
		return $this->center;
	}

	/**
	 * 
	 * @param PageElement|null $center
	 * @return \PageSplit
	 */
	public function setCenter($center) {
		$this->center = $center;
		return $this;
	}

	/**
	 * 
	 * @return PageElement|null
	 */
	public function getRight() {
		return $this->right;
	}

	/**
	 * 
	 * @param PageElement|null $right
	 * @return \PageSplit
	 */
	public function setRight($right) {
		$this->right = $right;
		return $this;
	}

	public function toXML() {
		$table = new XMLElement('div');
		$table->setAttribute('class', 'table');
		$properties = $this->getProperties();
		foreach ($properties as $name => $value) {
			$table->addAttribute($name, $value);
		}
		$table->addChild($row = new XMLElement('div'));
		$row->setAttribute('class', 'row');
		if ($this->left !== NULL) {
			$row->addChild($left = new XMLElement('div'));
			$left->setAttribute('class', 'cell left');
			$left->addChild($this->left->toXML());
		}
		if ($this->center !== NULL) {
			$row->addChild($center = new XMLElement('div'));
			$center->setAttribute('class', 'cell center');
			$center->addChild($this->center->toXML());
		}
		if ($this->right !== NULL) {
			$row->addChild($right = new XMLElement('div'));
			$right->setAttribute('class', 'cell right');
			$right->addChild($this->right->toXML());
		}
		return $table;
	}

}
